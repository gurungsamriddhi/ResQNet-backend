<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DonationResource;
use App\Models\Donation;
use App\Services\KhaltiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use RuntimeException;

class DonationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $donations = $request->user()
            ->donations()
            ->with(['organization'])
            ->latest()
            ->paginate(20);

        return response()->json([
            'success' => true,
            'message' => $donations->isEmpty()
                ? "You haven't made any donations yet."
                : "Donations retrieved successfully.",
            'data' => DonationResource::collection($donations),
            'meta' => [
                'current_page' => $donations->currentPage(),
                'last_page' => $donations->lastPage(),
                'total' => $donations->total(),
                'per_page' => $donations->perPage(),
            ],
        ]);
    }

    /**
     *Initiate KHalti Payment (Main endpoint for mobile app)
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
        $purchaseOrderId = 'DON-' . strtoupper(uniqid());

        $donation = Donation::create([
            'user_id' => $request->user()->id,
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
            if (!$isAnonymous) {
                if ($request->donor_name)  $customerInfo['name'] = $request->donor_name;
                if ($request->donor_email) $customerInfo['email'] = $request->donor_email;
                if ($request->donor_phone) $customerInfo['phone'] = $request->donor_phone;
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

            report($e);

            return response()->json([
                'message' => 'Payment initiation failed.',
            ], 422);
        }
    }

    /**
     * Khalti Callback (Khalti redirects the browser/WebView here after payment).
     * Public route: the WebView carries no API token, so the pidx is the only key.
     */
    public function khaltiCallback(Request $request, KhaltiService $khalti): JsonResponse
    {
        $pidx = $request->query('pidx');

        if (!$pidx) {
            return response()->json(['message' => 'Invalid payment response.'], 400);
        }

        $donation = Donation::where('pidx', $pidx)->firstOrFail();

        try {
            $result = $khalti->settle($donation, $khalti->lookupPayment($pidx));
        } catch (RuntimeException $e) {
            report($e);

            return response()->json(['message' => 'Could not verify payment.'], 422);
        }

        if ($result === 'completed') {
            return response()->json([
                'message' => 'Thank you! Donation completed successfully.',
                'donation' => new DonationResource($donation->fresh()),
            ]);
        }

        if ($result === 'pending') {
            $donation->update(['status' => 'failed']);
        }

        return response()->json([
            'message' => 'Payment was not successful.',
            'donation' => new DonationResource($donation->fresh()),
        ], 422);
    }

    /**
     * Verify Payment (mobile app calls this after the WebView closes).
     */
    public function khaltiVerify(Request $request, KhaltiService $khalti): JsonResponse
    {
        $request->validate(['pidx' => 'required|string']);

        // Only the donor can verify their own donation
        $donation = Donation::where('pidx', $request->pidx)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        try {
            $result = $khalti->settle($donation, $khalti->lookupPayment($request->pidx));
        } catch (RuntimeException $e) {
            report($e);

            return response()->json(['message' => 'Verification failed.'], 422);
        }

        if ($result === 'completed') {
            return response()->json([
                'message' => 'Donation completed successfully.',
                'donation' => new DonationResource($donation->fresh()),
            ]);
        }

        if ($result === 'amount_mismatch') {
            return response()->json(['message' => 'Payment amount did not match the donation.'], 422);
        }

        return response()->json([
            'message' => 'Payment not yet completed.',
            'status' => $donation->fresh()->status,
        ]);
    }

    /**
     *Get single doantion status
     */
    public function status(Request $request,Donation $donation): JsonResponse
    {
        if ($donation->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        return response()->json([
            'donation' => new DonationResource($donation),
        ]);
    }
}
