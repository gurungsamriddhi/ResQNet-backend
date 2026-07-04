<?php

namespace Database\Factories;

use App\Models\Pet;
use App\Models\Vet;
use Illuminate\Database\Eloquent\Factories\Factory;

class MedicalRecordFactory extends Factory
{
    public function definition(): array
    {
        return [
            'pet_id' => Pet::factory(),
            'vet_id' => Vet::factory(),
            'type' => $this->faker->randomElement(['checkup', 'treatment', 'surgery', 'injury', 'observation']),
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'record_date' => $this->faker->date(),
        ];
    }
}
