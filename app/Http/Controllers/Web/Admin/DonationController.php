<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Mail\DonationAppreciation;
use App\Models\Donation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class DonationController extends Controller
{
    public function index(): View
    {
        $totalCollected = Donation::query()
            ->where('status', 'completed')
            ->sum('amount');

        $donations = Donation::query()
            ->where('status', 'completed')
            ->latest()
            ->paginate(25);

        return view('admin.donations.index', [
            'totalCollected' => $totalCollected,
            'donations' => $donations,
        ]);
    }

    public function sendAppreciation(Donation $donation): RedirectResponse
    {
        if ($donation->status !== 'completed') {
            return back()->withErrors(['error' => 'Cannot send appreciation for an incomplete donation.']);
        }

        $recipient = $donation->donor_email;
        if (! $recipient) {
            return back()->withErrors(['error' => 'No email address available for this donor.']);
        }

        $displayName = $donation->is_anonymous || blank($donation->donor_name)
            ? 'Valued Supporter'
            : $donation->donor_name;

        Mail::to($recipient)->send(new DonationAppreciation(
            donorName: $displayName,
            date: $donation->created_at,
            amount: $donation->amount,
        ));

        return back()->with('success', "Appreciation email sent to {$recipient}.");
    }
}
