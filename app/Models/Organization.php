<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Organization extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'description',
        'logo',                    // if you're storing logo path directly
        'phone',
        'email',
        'address',
        'registration_number',
        'latitude',
        'longitude',
        'is_verified',
        'verified_at',
        'status',
        'rejection_reason',
        'suspension_reason',
        'fcm_token',
    ];

    public function routeNotificationForFcm()
    {
        return $this->fcm_token;
    }

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_verified'   => 'boolean',
            'verified_at'   => 'datetime',
            'latitude'      => 'decimal:8',
            'longitude'     => 'decimal:8',
            'status'        => 'string', // or you can use Enum if using Laravel 8.4+
        ];
    }

    /**
     * The attributes that should be hidden (if needed).
     *
     * @var list<string>
     */
    protected $hidden = [];

    /**
     * Relationships
     */

    /**
     * The user who owns this organization.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Pets belonging to this organization.
     */
    public function pets()
    {
        return $this->hasMany(Pet::class);
    }

    /**
     * Vets under this organization.
     */
    public function vets()
    {
        return $this->hasMany(Vet::class);
    }

    /**
     * Rescue reports submitted to this organization.
     */
    public function rescueReports()
    {
        return $this->hasMany(RescueReport::class);
    }

    /**
     * Donations received by this organization.
     */
    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    /**
     * Team members belonging to this organization.
     */
    public function organizationTeams()
    {
        return $this->hasMany(OrganizationTeam::class);
    }

    /**
     * Media Library Collections
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('registration_documents')
             ->singleFile()           // only one registration document
             ->acceptsMimeTypes(['application/pdf', 'image/jpeg', 'image/png', 'image/jpg']);

        $this->addMediaCollection('logo')
             ->singleFile()
             ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/jpg', 'image/webp']);
    }

    /**
     * Accessors & Mutators (Optional but useful)
     */

    public function getIsPendingAttribute(): bool
    {
        return $this->status === 'pending';
    }

    public function getIsActiveAttribute(): bool
    {
        return $this->status === 'active';
    }

    public function getIsVerifiedAttribute($value = null): bool
    {
        return (bool) ($value ?? false) && $this->verified_at !== null;
    }
}