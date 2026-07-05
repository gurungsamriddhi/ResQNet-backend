<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Pet extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'organization_id',
        'created_by',
        'qr_code_id',
        'name',
        'species',
        'breed',
        'age_months',
        'gender',
        'size',
        'color',
        'description',
        'health_status',
        'is_vaccinated',
        'is_neutered',
        'status',
        'suspension_reason',
        'adopted_at',
    ];

    protected function casts(): array
    {
        return [
            'is_vaccinated' => 'boolean',
            'is_neutered' => 'boolean',
            'age_months' => 'integer',
            'adopted_at' => 'datetime',
        ];
    }

    // ─── Media Collections ────────────────────────────────────

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images');
    }

    // ─── Relationships ────────────────────────────────────────

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function adoptionRequests(): HasMany
    {
        return $this->hasMany(AdoptionRequest::class);
    }

    public function medicalRecords(): HasMany
    {
        return $this->hasMany(MedicalRecord::class)->orderByDesc('record_date');
    }

    public function vaccinations(): HasMany
    {
        return $this->hasMany(Vaccination::class)->orderByDesc('date_administered');
    }

    public function medications(): HasMany
    {
        return $this->hasMany(Medication::class)->orderByDesc('start_date');
    }

    public function rescueReports(): HasMany
    {
        return $this->hasMany(RescueReport::class);
    }

    public function savedByUsers()
    {
        return $this->belongsToMany(User::class, 'saved_pets')->withTimestamps();
    }

    // ─── Query Scopes ────────────────────────────────────────

    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    public function scopeSuspended($query)
    {
        return $query->where('status', 'suspended');
    }

    public function scopeActive($query)
    {
        return $query->whereNotIn('status', ['suspended', 'inactive']);
    }

    // ─── Helpers ─────────────────────────────────────────────

    public function isSuspended(): bool
    {
        return $this->status === 'suspended';
    }

    // ─── Boot ────────────────────────────────────────────────

    protected static function booted()
    {
        static::creating(function ($pet) {
            if (!$pet->qr_code_id) {
                $maxId = static::withTrashed()->max('id') ?? 0;
                $pet->qr_code_id = 'RQN-' . str_pad($maxId + 1, 5, '0', STR_PAD_LEFT);
            }
        });
    }
}
