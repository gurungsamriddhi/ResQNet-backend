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
                'is_global' => false,
                'name' => 'Dr. Anisha Gurung',
                'phone' => '+9779843333333',
                'email' => 'anisha.gurung@pawsandclaws.np',
                'address' => 'Lakeside-6, Pokhara, Kaski',
                'specialization' => 'General Practice',
                'license_number' => 'VET-NP-10234',
                'status' => 'active',
            ],
            [
                'organization_id' => $paws->id,
                'is_global' => false,
                'name' => 'Dr. Bikash Thapa',
                'phone' => '+9779844444444',
                'email' => 'bikash.thapa@pawsandclaws.np',
                'address' => 'Lakeside-6, Pokhara, Kaski',
                'specialization' => 'Surgery',
                'license_number' => 'VET-NP-10567',
                'status' => 'active',
            ],
            [
                'organization_id' => $kar->id,
                'is_global' => false,
                'name' => 'Dr. Meera Shrestha',
                'phone' => '+9779845555555',
                'email' => 'meera@kar.org.np',
                'address' => 'Baneshwor-4, Kathmandu',
                'specialization' => 'Emergency Care',
                'license_number' => 'VET-NP-10891',
                'status' => 'active',
            ],
            [
                'organization_id' => null,
                'is_global' => true,
                'name' => 'Dr. Rajesh Hamal',
                'phone' => '+9779846666666',
                'email' => 'rajesh.hamal@vetmail.np',
                'address' => 'Durbar Marg, Kathmandu',
                'specialization' => 'Internal Medicine',
                'license_number' => 'VET-NP-20001',
                'status' => 'active',
            ],
            [
                'organization_id' => null,
                'is_global' => true,
                'name' => 'Dr. Sunita Rai',
                'phone' => '+9779847777777',
                'email' => 'sunita.rai@vetmail.np',
                'address' => 'New Road, Kathmandu',
                'specialization' => 'Dermatology',
                'license_number' => 'VET-NP-20002',
                'status' => 'active',
            ],
        ];

        foreach ($vets as $vet) {
            Vet::firstOrCreate(
                ['license_number' => $vet['license_number']],
                $vet
            );
        }
    }
}
