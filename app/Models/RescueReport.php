<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class RescueReport extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'organization_id',
        'pet_id',
        'case_code',
        'title',
        'description',
        'image_path',
        'latitude',
        'longitude',
        'address',
        'urgency',
        'status',
        'resolved_at',
        'assigned_at',
        'in_progress_at',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
            'resolved_at' => 'datetime',
            'assigned_at' => 'datetime',
            'in_progress_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (RescueReport $report) {
            if (!$report->case_code) {
                $report->case_code = 'RSQ-' . strtoupper(substr(uniqid(), -5));
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function pet(): BelongsTo
    {
        return $this->belongsTo(Pet::class);
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(RescueAssignment::class);
    }

    public function teamMembers(): HasMany
    {
        return $this->assignments();
    }

    public function histories(): HasMany
    {
        return $this->hasMany(RescueCaseHistory::class);
    }

    public function scopeReported($query)
    {
        return $query->where('status', 'reported');
    }

    public function scopeAssignable($query)
    {
        return $query->whereNull('organization_id')->where('status', 'reported');
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['reported', 'assigned', 'in_progress']);
    }

    public function isClaimed(): bool
    {
        return $this->organization_id !== null;
    }

    public function canBeClaimed(): bool
    {
        return $this->organization_id === null && $this->status === 'reported';
    }
}
