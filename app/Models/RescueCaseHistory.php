<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RescueCaseHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'rescue_report_id',
        'from_status',
        'to_status',
        'changed_by',
        'notes',
    ];

    public function rescueReport(): BelongsTo
    {
        return $this->belongsTo(RescueReport::class);
    }

    public function changedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
