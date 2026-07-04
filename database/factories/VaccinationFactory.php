<?php

namespace Database\Factories;

use App\Models\Pet;
use App\Models\Vet;
use Illuminate\Database\Eloquent\Factories\Factory;

class VaccinationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'pet_id' => Pet::factory(),
            'vet_id' => Vet::factory(),
            'vaccine_name' => $this->faker->word() . ' Vaccine',
            'batch_number' => $this->faker->bothify('BATCH-####'),
            'date_administered' => $this->faker->date(),
        ];
    }
}
