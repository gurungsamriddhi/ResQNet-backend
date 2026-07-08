<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;

class OrganizationTeam extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'organization_id',
        'name',
        'email',
        'phone',
        'role',
        'status',
        'fcm_token',
    ];

    public function routeNotificationForFcm()
    {
        return $this->fcm_token;
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function rescueAssignments(): HasMany
    {
        return $this->hasMany(RescueAssignment::class);
    }
}
