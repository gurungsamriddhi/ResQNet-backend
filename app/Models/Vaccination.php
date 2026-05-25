<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vaccination extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'pet_id',
        'vet_id',
        'vaccine_name',
        'batch_number',
        'date_administered',
        'next_due_date',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'date_administered' => 'date',
            'next_due_date' => 'date',
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
