<?php

use App\Models\Organization;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('organization.{orgId}', function ($user, $orgId) {
    if ($user->organization && $user->organization->id === (int) $orgId) {
        return ['id' => $user->id, 'name' => $user->name];
    }

    return false;
});

// Mobile user channel — used by AdoptionStatusUpdatedEvent
// The mobile client subscribes to `private-user.{userId}` after login
Broadcast::channel('user.{userId}', function ($user, $userId) {
    return $user->id === (int) $userId;
});
