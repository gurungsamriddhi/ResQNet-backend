<?php

namespace App\Policies;

use App\Models\RescueReport;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class RescueReportPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('super_admin') || $user->hasRole('organization');
    }

    public function view(User $user, RescueReport $rescueReport): bool
    {
        if ($user->hasRole('super_admin')) return true;
        if ($user->hasRole('organization')) {
            return $rescueReport->organization_id === $user->organization?->id;
        }
        return $rescueReport->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function assign(User $user, RescueReport $rescueReport): bool
    {
        return $user->hasRole('super_admin') || $user->hasRole('organization');
    }

    public function accept(User $user, RescueReport $rescueReport): bool
    {
        return $user->hasRole('organization') && $rescueReport->canBeClaimed();
    }

    public function update(User $user, RescueReport $rescueReport): bool
    {
        if ($user->hasRole('super_admin')) return true;
        if ($user->hasRole('organization')) {
            return $rescueReport->organization_id === $user->organization?->id;
        }
        return false;
    }

    public function delete(User $user, RescueReport $rescueReport): bool
    {
        return $user->hasRole('super_admin');
    }
}
