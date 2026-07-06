<?php

namespace App\Policies;

use App\Models\AdoptionRequest;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AdoptionRequestPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('super_admin') || $user->hasRole('organization');
    }

    public function view(User $user, AdoptionRequest $adoptionRequest): bool
    {
        if ($user->hasRole('super_admin')) return true;
        if ($user->hasRole('organization')) {
            return $adoptionRequest->pet?->organization_id === $user->organization?->id;
        }
        return $adoptionRequest->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function review(User $user, AdoptionRequest $adoptionRequest): bool
    {
        if ($user->hasRole('super_admin')) return true;
        if ($user->hasRole('organization')) {
            return $adoptionRequest->pet?->organization_id === $user->organization?->id;
        }
        return false;
    }

    public function delete(User $user, AdoptionRequest $adoptionRequest): bool
    {
        return $user->hasRole('super_admin');
    }
}
