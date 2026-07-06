<?php

namespace App\Policies;

use App\Models\Pet;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PetPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Pet $pet): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('organization');
    }

    public function update(User $user, Pet $pet): bool
    {
        if ($user->hasRole('super_admin')) return true;
        if ($user->hasRole('organization')) {
            return $pet->organization_id === $user->organization?->id;
        }
        return false;
    }

    public function delete(User $user, Pet $pet): bool
    {
        return $user->hasRole('super_admin');
    }

    public function suspend(User $user, Pet $pet): bool
    {
        return $user->hasRole('super_admin');
    }

    public function reactivate(User $user, Pet $pet): bool
    {
        return $user->hasRole('super_admin');
    }
}
