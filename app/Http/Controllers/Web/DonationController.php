<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDonationRequest;
use App\Models\Donation;
use App\Models\Pet;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

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

        return view('public.donate', [
            'heroPet' => $heroPet,
            'heroImageUrl' => $heroImageUrl,
            'presetAmounts' => [100, 250, 500, 1000],
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

    public function store(StoreDonationRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $isAnonymous = $request->boolean('is_anonymous');

        Donation::create([
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

        return redirect()
            ->route('donate')
            ->with('success', 'Thank you! Your donation has been recorded and is being processed securely.');
    }
}
