<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class MedicalRecord extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'pet_id',
        'vet_id',
        'type',
        'title',
        'description',
        'notes',
        'record_date',
        'next_visit_date',
    ];

    protected function casts(): array
    {
        return [
            'record_date' => 'date',
            'next_visit_date' => 'date',
            'deleted_at' => 'datetime',
        ];
    }

    public function pet(): BelongsTo
    {
        return $this->belongsTo(Pet::class);
    }

    public function vet(): BelongsTo
    {
        return $this->belongsTo(Vet::class);
    }
}
