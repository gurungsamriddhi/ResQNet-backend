<?php

namespace App\Policies;

use App\Models\Donation;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DonationPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('super_admin') || $user->hasRole('organization');
    }

    public function view(User $user, Donation $donation): bool
    {
        if ($user->hasRole('super_admin')) return true;
        if ($user->hasRole('organization')) {
            return $donation->organization_id === $user->organization?->id;
        }
        return $donation->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Donation $donation): bool
    {
        return $user->hasRole('super_admin');
    }

    public function delete(User $user, Donation $donation): bool
    {
        return $user->hasRole('super_admin');
    }
}
