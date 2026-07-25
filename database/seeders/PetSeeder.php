<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\Pet;
use Illuminate\Database\Seeder;

class PetSeeder extends Seeder
{
    public function run(): void
    {
        $paws = Organization::where('slug', 'paws-and-claws')->firstOrFail();
        $kar = Organization::where('slug', 'kathmandu-animal-rescue')->firstOrFail();

        $pets = [
            [
                'organization_id' => $paws->id,
                'qr_code_id' => 'RQN-00001',
                'name' => 'Max',
                'species' => 'dog',
                'breed' => 'Golden Retriever',
                'age_months' => 24,
                'gender' => 'male',
                'size' => 'large',
                'color' => 'Golden',
                'description' => 'Friendly and energetic. Loves outdoor walks and children.',
                'health_status' => 'Healthy. Up to date on core vaccines.',
                'is_vaccinated' => true,
                'is_neutered' => true,
                'status' => 'available',
            ],
            [
                'organization_id' => $paws->id,
                'qr_code_id' => 'RQN-00002',
                'name' => 'Luna',
                'species' => 'dog',
                'breed' => 'Mixed Breed',
                'age_months' => 18,
                'gender' => 'female',
                'size' => 'medium',
                'color' => 'Black and white',
                'description' => 'Calm and affectionate rescue who warms up quickly to new families.',
                'health_status' => 'Recovering from minor dental work. Neutering scheduled.',
                'is_vaccinated' => true,
                'is_neutered' => false,
                'status' => 'available',
            ],
            [
                'organization_id' => $paws->id,
                'qr_code_id' => 'RQN-00003',
                'name' => 'Milo',
                'species' => 'cat',
                'breed' => 'Domestic Shorthair',
                'age_months' => 12,
                'gender' => 'male',
                'size' => 'small',
                'color' => 'Tabby',
                'description' => 'Playful indoor cat. Good with other cats.',
                'health_status' => 'Fully vaccinated and neutered.',
                'is_vaccinated' => true,
                'is_neutered' => true,
                'status' => 'unavailable',
            ],
            [
                'organization_id' => $kar->id,
                'qr_code_id' => 'RQN-00004',
                'name' => 'Rocky',
                'species' => 'dog',
                'breed' => 'Beagle',
                'age_months' => 36,
                'gender' => 'male',
                'size' => 'medium',
                'color' => 'Tri-color',
                'description' => 'Playful and curious with lots of energy.',
                'health_status' => 'Healthy. Microchip pending.',
                'is_vaccinated' => true,
                'is_neutered' => true,
                'status' => 'available',
            ],
            [
                'organization_id' => $kar->id,
                'qr_code_id' => 'RQN-00005',
                'name' => 'Bella',
                'species' => 'cat',
                'breed' => 'Persian Mix',
                'age_months' => 48,
                'gender' => 'female',
                'size' => 'medium',
                'color' => 'White',
                'description' => 'Quiet senior cat looking for a calm home.',
                'health_status' => 'Senior wellness monitored monthly.',
                'is_vaccinated' => true,
                'is_neutered' => true,
                'status' => 'adopted',
                'adopted_at' => now()->subWeeks(2),
            ],
            [
                'organization_id' => $kar->id,
                'qr_code_id' => 'RQN-00006',
                'name' => 'Charlie',
                'species' => 'rabbit',
                'breed' => 'Holland Lop',
                'age_months' => 8,
                'gender' => 'male',
                'size' => 'small',
                'color' => 'Brown',
                'description' => 'Gentle rabbit suitable for experienced owners.',
                'health_status' => 'Routine checkup completed.',
                'is_vaccinated' => false,
                'is_neutered' => false,
                'status' => 'available',
            ],
        ];

        foreach ($pets as $data) {
            $pet = Pet::firstOrCreate(
                ['qr_code_id' => $data['qr_code_id']],
                $data
            );

            $imagePath = storage_path('app/public/pets/seed/' . $pet->qr_code_id . '.jpg');
            if (file_exists($imagePath)) {
                $pet->addMedia($imagePath)->toMediaCollection('images');
            }
        }
    }
}
