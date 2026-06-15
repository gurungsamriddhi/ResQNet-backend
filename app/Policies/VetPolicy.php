<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Vet;
use Illuminate\Auth\Access\Response;

class VetPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('super_admin') || $user->hasRole('organization');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Vet $vet): bool
    {
        if ($user->hasRole('super_admin')) {
            return true;
        }

        if ($user->hasRole('organization')) {
            // Can view global vets, or vets owned by their org
            return $vet->isGlobal() || $vet->isOwnedBy($user->organization?->id);
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('super_admin') || $user->hasRole('organization');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Vet $vet): bool
    {
        if ($user->hasRole('super_admin')) {
            return $vet->isGlobal(); // Super admin manages global vets
        }

        if ($user->hasRole('organization')) {
            return $vet->isOwnedBy($user->organization?->id); // Org manages its own vets
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Vet $vet): bool
    {
        return $this->update($user, $vet); // Same rules as update
    }
}
