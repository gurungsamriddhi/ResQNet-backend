<?php

namespace App\Http\Controllers\Web;

use App\Events\DonationCompletedEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDonationRequest;
use App\Models\Donation;
use App\Models\Pet;
use App\Services\KhaltiService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use RuntimeException;

class DonationController extends Controller
{
    public function create(): View
    {
        $heroPet = Pet::query()
            ->whereHas('media', fn ($query) => $query->where('collection_name', 'images'))
            ->latest()
            ->first();

        $heroImageUrl = $heroPet?->getFirstMediaUrl('images');

        if (! $heroImageUrl) {
            $heroImageUrl = 'https://images.unsplash.com/photo-1450778869180-41d0601e046e?auto=format&fit=crop&w=1200&q=80';
        }

        $recentDonors = Donation::query()
            ->where('status', 'completed')
            ->latest()
            ->take(50)
            ->get();

        return view('public.donate', [
            'heroPet' => $heroPet,
            'heroImageUrl' => $heroImageUrl,
            'presetAmounts' => [100, 250, 500, 1000],
            'recentDonors' => $recentDonors,
            'paymentMethods' => [
                ['id' => 'khalti', 'label' => 'Khalti'],
            ],
            'impactStats' => [
                ['value' => '10K+', 'label' => 'Users Connected', 'description' => 'People using the platform to help others'],
                ['value' => '5K+', 'label' => 'Rescue Cases Reported', 'description' => 'Cases reported and supported through the platform'],
                ['value' => '2K+', 'label' => 'Lives Touched', 'description' => 'Making a real impact every day'],
                ['value' => '100%', 'label' => 'Community Driven', 'description' => 'Run with compassion, powered by people like you'],
            ],
            'allocationBreakdown' => [
                ['percent' => '40%', 'title' => 'Platform Maintenance', 'description' => 'Servers, hosting and regular maintenance'],
                ['percent' => '25%', 'title' => 'Feature Development', 'description' => 'Building new features and improvements'],
                ['percent' => '15%', 'title' => 'Security & Data Protection', 'description' => 'Keeping data safe and platform secure'],
                ['percent' => '10%', 'title' => 'Awareness & Outreach', 'description' => 'Spreading awareness and reaching more people'],
                ['percent' => '10%', 'title' => 'Operations & Other', 'description' => 'Daily operations and miscellaneous costs'],
            ],
        ]);
    }

    public function store(StoreDonationRequest $request, KhaltiService $khalti): RedirectResponse
    {
        $validated = $request->validated();
        $isAnonymous = $request->boolean('is_anonymous');

        $purchaseOrderId = 'DON-' . strtoupper(uniqid());

        $donation = Donation::create([
            'user_id' => auth()->id(),
            'organization_id' => $validated['organization_id'] ?? null,
            'purchase_order_id' => $purchaseOrderId,
            'donor_name' => $isAnonymous ? null : ($validated['donor_name'] ?? null),
            'donor_email' => $validated['donor_email'],
            'donor_phone' => $isAnonymous ? null : ($validated['donor_phone'] ?? null),
            'is_anonymous' => $isAnonymous,
            'frequency' => $validated['frequency'],
            'amount' => $validated['amount'],
            'payment_method' => 'khalti',
            'status' => 'pending',
            'remarks' => $validated['remarks'] ?? null,
        ]);

        try {
            $customerInfo = [];
            if (! $isAnonymous && $validated['donor_name']) {
                $customerInfo['name'] = $validated['donor_name'];
            }
            if (! $isAnonymous && $validated['donor_email']) {
                $customerInfo['email'] = $validated['donor_email'];
            }
            if (! $isAnonymous && ($validated['donor_phone'] ?? null)) {
                $customerInfo['phone'] = $validated['donor_phone'];
            }

            $result = $khalti->initiatePayment([
                'return_url' => route('donate.callback'),
                'amount' => $validated['amount'],
                'purchase_order_id' => $purchaseOrderId,
                'purchase_order_name' => 'Donation to ResQNet',
                'customer_info' => ! empty($customerInfo) ? $customerInfo : null,
            ]);

            $donation->update([
                'pidx' => $result['pidx'],
                'payment_payload' => $result,
            ]);

            return redirect()->away($result['payment_url']);
        } catch (RuntimeException $e) {
            $donation->update([
                'status' => 'failed',
                'payment_payload' => ['error' => $e->getMessage()],
            ]);

            return redirect()
                ->route('donate')
                ->withInput()
                ->withErrors(['payment' => 'Payment could not be initiated. Please try again.']);
        }
    }

    public function khaltiCallback(Request $request, KhaltiService $khalti): RedirectResponse
    {
        $pidx = $request->query('pidx');

        if (! $pidx) {
            return redirect()->route('donate')->withErrors(['payment' => 'Invalid payment response.']);
        }

        $donation = Donation::where('pidx', $pidx)->first();

        if (! $donation) {
            return redirect()->route('donate')->withErrors(['payment' => 'Donation record not found.']);
        }

        try {
            $lookup = $khalti->lookupPayment($pidx);

            $donation->update([
                'payment_payload' => $lookup,
            ]);

            if ($khalti->isCompleted($lookup)) {
                $donation->update([
                    'status' => 'completed',
                    'transaction_id' => $lookup['transaction_id'] ?? null,
                ]);

                DonationCompletedEvent::dispatch($donation);

                return redirect()
                    ->route('donate')
                    ->with('success', 'Thank you! Your donation has been completed successfully.');
            }

            $donation->update(['status' => 'failed']);

            return redirect()
                ->route('donate')
                ->withErrors(['payment' => 'Payment was not completed. Please try again.']);
        } catch (RuntimeException $e) {
            $donation->update([
                'payment_payload' => array_merge(
                    $donation->payment_payload ?? [],
                    ['lookup_error' => $e->getMessage()]
                ),
            ]);

            return redirect()
                ->route('donate')
                ->withErrors(['payment' => 'Could not verify payment status. Please contact support.']);
        }
    }
}
