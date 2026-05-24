<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
            'is_vaccinated' => 'boolean',
            'is_neutered' => 'boolean',
            'age_months' => 'integer',
            'adopted_at' => 'datetime',
        ];
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function images()
    {
        return $this->hasMany(PetImage::class)->orderBy('sort_order');
    }

    public function primaryImage()
    {
        return $this->hasOne(PetImage::class)->where('is_primary', true);
    }

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
