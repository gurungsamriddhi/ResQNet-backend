<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pet extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'organization_id',
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
        'adopted_at',
    ];

    protected function casts(): array
    {
        return [
            'age_months' => 'integer',
            'is_vaccinated' => 'boolean',
            'is_neutered' => 'boolean',
            'adopted_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Pet $pet) {
            if (empty($pet->qr_code_id)) {
                $pet->qr_code_id = static::generateQrCodeId();
            }
        });
    }

    public static function generateQrCodeId(): string
    {
        do {
            $id = 'RQN-'.str_pad((string) random_int(1, 99999), 5, '0', STR_PAD_LEFT);
        } while (static::where('qr_code_id', $id)->exists());

        return $id;
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(PetImage::class);
    }

    public function adoptionRequests(): HasMany
    {
        return $this->hasMany(AdoptionRequest::class);
    }

    public function rescueReports(): HasMany
    {
        return $this->hasMany(RescueReport::class);
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
}
