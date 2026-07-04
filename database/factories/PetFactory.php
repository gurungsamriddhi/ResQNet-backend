<?php

namespace Database\Factories;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PetFactory extends Factory
{
    public function definition(): array
    {
        return [
            'organization_id' => Organization::factory(),
            'created_by' => User::factory(),
            'name' => $this->faker->firstName(),
            'species' => $this->faker->randomElement(['dog', 'cat', 'bird', 'rabbit', 'other']),
            'breed' => $this->faker->word(),
            'age_months' => $this->faker->numberBetween(1, 120),
            'gender' => $this->faker->randomElement(['male', 'female', 'unknown']),
            'size' => $this->faker->randomElement(['small', 'medium', 'large', 'xl']),
            'color' => $this->faker->safeColorName(),
            'description' => $this->faker->paragraph(),
            'health_status' => 'Healthy',
            'is_vaccinated' => $this->faker->boolean(),
            'is_neutered' => $this->faker->boolean(),
            'status' => 'available',
        ];
    }
}
