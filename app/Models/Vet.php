<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vet extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'organization_id',
        'is_global',
        'name',
        'phone',
        'email',
        'address',
        'specialization',
        'license_number',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'is_global' => 'boolean',
            'deleted_at' => 'datetime',
        ];
    }

    public function isGlobal(): bool
    {
        return $this->is_global;
    }

    public function isOwnedBy(?int $orgId): bool
    {
        return !$this->is_global && $this->organization_id === $orgId && $orgId !== null;
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function medicalRecords(): HasMany
    {
        return $this->hasMany(MedicalRecord::class);
    }

    public function vaccinations(): HasMany
    {
        return $this->hasMany(Vaccination::class);
    }

    public function medications(): HasMany
    {
        return $this->hasMany(Medication::class);
    }

    public function scopeGlobal($query)
    {
        return $query->where('is_global', true);
    }

    public function scopeOwnedByOrganization($query, $organizationId)
    {
        return $query->where('is_global', false)
                     ->where('organization_id', $organizationId);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeAvailableForOrganization($query, $organizationId)
    {
        return $query->where('is_global', true)
                     ->orWhere(function ($q) use ($organizationId) {
                         $q->where('is_global', false)
                           ->where('organization_id', $organizationId);
                     });
    }
}
