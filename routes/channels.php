<?php

use App\Models\Organization;
use Illuminate\Support\Facades\Broadcast;

//organization private channel channel name organization.12{orgID}
//only allows users who belong to that organization to listen to this channel
Broadcast::channel('organization.{orgId}', function ($user, $orgId) {
    if ($user->organization && $user->organization->id === (int) $orgId) {
        return ['id' => $user->id, 'name' => $user->name];
    }

    return false;
});

//rescue-updates channel this is a public-style channel but restricted only organization staff or super admins listen to rescue updates
Broadcast::channel('rescue-updates', function ($user) {
    if ($user->hasRole('organization') || $user->hasRole('super_admin')) {
        return ['id' => $user->id, 'name' => $user->name, 'organization_id' => $user->organization_id];
    }

    return false;
});

// Mobile user channel — used by AdoptionStatusUpdatedEvent
// The mobile client subscribes to `private-user.{userId}` after login
//channel name user.userid this is used for sending perosnal notification to mobile users 
//only the owner can listen 
Broadcast::channel('user.{userId}', function ($user, $userId) {
    return $user->id === (int) $userId;
});
