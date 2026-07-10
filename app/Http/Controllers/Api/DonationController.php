<?php

namespace App\Http\Controllers\Api;

use App\Events\DonationCompletedEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDonationRequest;
use App\Http\Resources\DonationResource;
use App\Models\Donation;
use App\Services\KhaltiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use RuntimeException;

class DonationController extends Controller
{
    public function index(): JsonResponse
    {
        $donations = Donation::query()
            ->with(['user', 'organization'])
            ->latest()
            ->paginate(20);

        return response()->json([
            'data' => DonationResource::collection($donations),
            'meta' => [
                'current_page' => $donations->currentPage(),
                'last_page' => $donations->lastPage(),
                'total' => $donations->total(),
            ],
        ]);
    }

    public function store(StoreDonationRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $isAnonymous = $request->boolean('is_anonymous');

        $donation = Donation::create([
            'user_id' => auth()->id(),
            'organization_id' => $validated['organization_id'] ?? null,
            'donor_name' => $isAnonymous ? null : ($validated['donor_name'] ?? null),
            'donor_email' => $isAnonymous ? null : ($validated['donor_email'] ?? null),
            'donor_phone' => $isAnonymous ? null : ($validated['donor_phone'] ?? null),
            'is_anonymous' => $isAnonymous,
            'frequency' => $validated['frequency'],
            'amount' => $validated['amount'],
            'payment_method' => $validated['payment_method'],
            'status' => 'pending',
            'remarks' => $validated['remarks'] ?? null,
        ]);

        return response()->json([
            'message' => 'Donation recorded successfully.',
            'donation' => new DonationResource($donation),
        ], 201);
    }

    /**
     * Step 1: Mobile app calls this to initiate Khalti payment.
     * Returns the Khalti payment URL the mobile app should open in a WebView.
     */
    public function khaltiInitiate(Request $request, KhaltiService $khalti): JsonResponse
    {
        $request->validate([
            'amount' => 'required|numeric|min:10',
            'organization_id' => 'nullable|exists:organizations,id',
            'donor_name' => 'nullable|string|max:255',
            'donor_email' => 'required|email|max:255',
            'donor_phone' => 'nullable|string|max:20',
            'is_anonymous' => 'sometimes|boolean',
            'frequency' => 'required|in:one_time,monthly',
            'remarks' => 'nullable|string|max:1000',
        ]);

        $isAnonymous = $request->boolean('is_anonymous');
        $purchaseOrderId = 'DON-'.strtoupper(uniqid());

        $donation = Donation::create([
            'user_id' => auth()->id(),
            'organization_id' => $request->organization_id,
            'purchase_order_id' => $purchaseOrderId,
            'donor_name' => $isAnonymous ? null : $request->donor_name,
            'donor_email' => $isAnonymous ? null : $request->donor_email,
            'donor_phone' => $isAnonymous ? null : $request->donor_phone,
            'is_anonymous' => $isAnonymous,
            'frequency' => $request->frequency,
            'amount' => $request->amount,
            'payment_method' => 'khalti',
            'status' => 'pending',
            'remarks' => $request->remarks,
        ]);

        try {
            $customerInfo = [];
            if (! $isAnonymous && $request->donor_name) {
                $customerInfo['name'] = $request->donor_name;
            }
            if (! $isAnonymous && $request->donor_email) {
                $customerInfo['email'] = $request->donor_email;
            }
            if (! $isAnonymous && $request->donor_phone) {
                $customerInfo['phone'] = $request->donor_phone;
            }

            $result = $khalti->initiatePayment([
                'return_url' => route('api.donations.khalti.callback'),
                'amount' => $request->amount,
                'purchase_order_id' => $purchaseOrderId,
                'purchase_order_name' => 'Donation to ResQNet',
                'customer_info' => ! empty($customerInfo) ? $customerInfo : null,
            ]);

            $donation->update([
                'pidx' => $result['pidx'],
                'payment_payload' => $result,
            ]);

            return response()->json([
                'message' => 'Khalti payment initiated.',
                'payment_url' => $result['payment_url'],
                'pidx' => $result['pidx'],
                'donation_id' => $donation->id,
            ]);
        } catch (RuntimeException $e) {
            $donation->update([
                'status' => 'failed',
                'payment_payload' => ['error' => $e->getMessage()],
            ]);

            return response()->json([
                'message' => 'Payment initiation failed.',
                'error' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Khalti return URL callback — mobile WebView redirects here after payment.
     * Verifies the payment and marks donation completed.
     */
    public function khaltiCallback(Request $request, KhaltiService $khalti): JsonResponse
    {
        $pidx = $request->query('pidx');

        if (! $pidx) {
            return response()->json(['message' => 'Invalid payment response.'], 400);
        }

        $donation = Donation::where('pidx', $pidx)->first();

        if (! $donation) {
            return response()->json(['message' => 'Donation record not found.'], 404);
        }

        try {
            $lookup = $khalti->lookupPayment($pidx);

            $donation->update(['payment_payload' => $lookup]);

            if ($khalti->isCompleted($lookup)) {
                $donation->update([
                    'status' => 'completed',
                    'transaction_id' => $lookup['transaction_id'] ?? null,
                ]);

                DonationCompletedEvent::dispatch($donation);

                return response()->json([
                    'message' => 'Donation completed successfully.',
                    'donation' => new DonationResource($donation->fresh()),
                ]);
            }

            $donation->update(['status' => 'failed']);

            return response()->json([
                'message' => 'Payment was not completed.',
                'donation' => new DonationResource($donation->fresh()),
            ], 422);
        } catch (RuntimeException $e) {
            $donation->update([
                'payment_payload' => array_merge(
                    $donation->payment_payload ?? [],
                    ['lookup_error' => $e->getMessage()]
                ),
            ]);

            return response()->json([
                'message' => 'Could not verify payment status.',
                'error' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Step 2: Mobile app calls this after Khalti WebView closes to verify payment.
     * This is the primary verification path for mobile apps.
     */
    public function khaltiVerify(Request $request, KhaltiService $khalti): JsonResponse
    {
        $request->validate([
            'pidx' => 'required|string',
        ]);

        $donation = Donation::where('pidx', $request->pidx)->first();

        if (! $donation) {
            return response()->json(['message' => 'Donation not found.'], 404);
        }

        try {
            $lookup = $khalti->lookupPayment($request->pidx);

            $donation->update(['payment_payload' => $lookup]);

            if ($khalti->isCompleted($lookup)) {
                $donation->update([
                    'status' => 'completed',
                    'transaction_id' => $lookup['transaction_id'] ?? null,
                ]);

                DonationCompletedEvent::dispatch($donation);

                return response()->json([
                    'message' => 'Donation completed successfully.',
                    'donation' => new DonationResource($donation->fresh()),
                ]);
            }

            return response()->json([
                'message' => 'Payment not yet completed.',
                'status' => $donation->status,
            ]);
        } catch (RuntimeException $e) {
            return response()->json([
                'message' => 'Verification failed.',
                'error' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Check donation status by donation ID.
     */
    public function status(Donation $donation): JsonResponse
    {
        if ($donation->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        return response()->json([
            'donation' => new DonationResource($donation),
        ]);
    }
}
