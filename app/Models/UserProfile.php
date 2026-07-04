<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;

    protected $table = 'user_profiles';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'housing_type',
        'employment_status',
        'family_members_count',
        'has_other_pets',
        'experience_with_pets',
        'bio',
        'occupation',
        'preferred_pet_type',
        'preferred_pet_size',
        'max_pets_allowed',
        'has_yard',
        'is_allergic',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'family_members_count' => 'integer',
            'has_other_pets'       => 'boolean',
            'experience_with_pets' => 'string',
            'has_yard'             => 'boolean',
            'is_allergic'          => 'boolean',
            'max_pets_allowed'     => 'integer',
        ];
    }

    // ─── Relationships ─────────────────────────────────

    /**
     * Get the user that owns this profile.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
