<?php

namespace Database\Factories;

use App\Models\Pet;
use App\Models\Vet;
use Illuminate\Database\Eloquent\Factories\Factory;

class MedicationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'pet_id' => Pet::factory(),
            'vet_id' => Vet::factory(),
            'medicine_name' => $this->faker->word(),
            'dosage' => '10mg',
            'frequency' => 'Twice a day',
            'start_date' => $this->faker->date(),
        ];
    }
}
