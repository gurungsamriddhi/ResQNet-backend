<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\OrganizationTeam;

class RescueAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'rescue_report_id',
        'organization_team_id',
        'role',
        'assigned_at',
    ];

    protected function casts(): array
    {
        return [
            'assigned_at' => 'datetime',
        ];
    }

    public function rescueReport(): BelongsTo
    {
        return $this->belongsTo(RescueReport::class);
    }

    public function teamMember(): BelongsTo
    {
        return $this->belongsTo(OrganizationTeam::class, 'organization_team_id');
    }
}
