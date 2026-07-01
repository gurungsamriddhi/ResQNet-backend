<?php

namespace Database\Factories;

use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;

class VetFactory extends Factory
{
    public function definition(): array
    {
        return [
            'organization_id' => Organization::factory(),
            'name' => 'Dr. ' . $this->faker->lastName(),
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->unique()->safeEmail(),
            'address' => $this->faker->address(),
            'specialization' => 'General Practice',
            'license_number' => $this->faker->unique()->bothify('NVC-####'),
            'is_global' => false,
            'status' => 'active',
        ];
    }
    
    public function global()
    {
        return $this->state(fn (array $attributes) => [
            'organization_id' => null,
            'is_global' => true,
        ]);
    }
}
