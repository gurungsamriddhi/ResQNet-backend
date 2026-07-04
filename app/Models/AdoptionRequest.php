<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdoptionRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'pet_id',
        'user_id',
        'message',
        'living_situation',
        'status',
        'organization_response',
        'reviewed_by',
        'reviewed_at',
        'response_sent_at',
        'adopted_at',
    ];

    protected function casts(): array
    {
        return [
            'reviewed_at' => 'datetime',
            'response_sent_at' => 'datetime',
            'adopted_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    public function pet(): BelongsTo
    {
        return $this->belongsTo(Pet::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer(){
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
