<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserProfileFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'housing_type' => $this->faker->randomElement(['apartment', 'house', 'condo', 'other']),
            'employment_status' => $this->faker->randomElement(['employed', 'unemployed', 'student', 'retired']),
            'family_members_count' => $this->faker->numberBetween(1, 8),
            'has_other_pets' => $this->faker->boolean(),
            'experience_with_pets' => $this->faker->randomElement(['none', 'beginner', 'intermediate', 'experienced']),
            'has_yard' => $this->faker->boolean(),
            'is_allergic' => $this->faker->boolean(),
        ];
    }
}
