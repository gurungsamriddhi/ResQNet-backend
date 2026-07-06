<?php

use App\Models\Organization;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('organization.{orgId}', function ($user, $orgId) {
    if ($user->organization && $user->organization->id === (int) $orgId) {
        return ['id' => $user->id, 'name' => $user->name];
    }

    return false;
});

Broadcast::channel('rescue-updates', function ($user) {
    if ($user->hasRole('organization') || $user->hasRole('super_admin')) {
        return ['id' => $user->id, 'name' => $user->name, 'organization_id' => $user->organization_id];
    }

    return false;
});
