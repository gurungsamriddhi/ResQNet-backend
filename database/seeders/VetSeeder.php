<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\Vet;
use Illuminate\Database\Seeder;

class VetSeeder extends Seeder
{
    public function run(): void
    {
        $paws = Organization::where('slug', 'paws-and-claws')->firstOrFail();
        $kar = Organization::where('slug', 'kathmandu-animal-rescue')->firstOrFail();

        $vets = [
            [
                'organization_id' => $paws->id,
                'name' => 'Dr. Anisha Gurung',
                'phone' => '+9779843333333',
                'email' => 'anisha.gurung@pawsandclaws.np',
                'specialization' => 'General Practice',
                'license_number' => 'VET-NP-10234',
                'is_active' => true,
            ],
            [
                'organization_id' => $paws->id,
                'name' => 'Dr. Bikash Thapa',
                'phone' => '+9779844444444',
                'email' => 'bikash.thapa@pawsandclaws.np',
                'specialization' => 'Surgery',
                'license_number' => 'VET-NP-10567',
                'is_active' => true,
            ],
            [
                'organization_id' => $kar->id,
                'name' => 'Dr. Meera Shrestha',
                'phone' => '+9779845555555',
                'email' => 'meera@kar.org.np',
                'specialization' => 'Emergency Care',
                'license_number' => 'VET-NP-10891',
                'is_active' => true,
            ],
        ];

        foreach ($vets as $vet) {
            Vet::firstOrCreate(
                [
                    'organization_id' => $vet['organization_id'],
                    'license_number' => $vet['license_number'],
                ],
                $vet
            );
        }
    }
}
