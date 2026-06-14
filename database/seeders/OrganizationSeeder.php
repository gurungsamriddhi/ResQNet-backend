<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    public function run(): void
    {
        $organizations = [
            [
                'user_email' => 'org1@resqnet.com',
                'name' => 'Paws & Claws Shelter',
                'slug' => 'paws-and-claws',
                'description' => 'A community shelter in Pokhara caring for dogs and cats awaiting adoption.',
                'phone' => '+9779841111111',
                'email' => 'contact@pawsandclaws.np',
                'address' => 'Lakeside-6, Pokhara, Kaski',
                'registration_number' => 'NGO-PKR-2024-001',
                'latitude' => 28.2096,
                'longitude' => 83.9856,
                'is_verified' => true,
                'verified_at' => now()->subMonths(3),
                'status' => 'active',
            ],
            [
                'user_email' => 'org2@resqnet.com',
                'name' => 'Kathmandu Animal Rescue',
                'slug' => 'kathmandu-animal-rescue',
                'description' => 'Urban rescue group serving the Kathmandu valley with emergency intake and rehoming.',
                'phone' => '+9779842222222',
                'email' => 'hello@kar.org.np',
                'address' => 'Baneshwor-4, Kathmandu',
                'registration_number' => 'NGO-KTM-2023-014',
                'latitude' => 27.6939,
                'longitude' => 85.3440,
                'is_verified' => true,
                'verified_at' => now()->subMonths(6),
                'status' => 'active',
            ],
        ];

        foreach ($organizations as $data) {
            $user = User::where('email', $data['user_email'])->firstOrFail();

            Organization::firstOrCreate(
                ['slug' => $data['slug']],
                [
                    'user_id' => $user->id,
                    'name' => $data['name'],
                    'description' => $data['description'],
                    'phone' => $data['phone'],
                    'email' => $data['email'],
                    'address' => $data['address'],
                    'registration_number' => $data['registration_number'],
                    'latitude' => $data['latitude'],
                    'longitude' => $data['longitude'],
                    'is_verified' => $data['is_verified'],
                    'verified_at' => $data['verified_at'],
                    'status' => $data['status'],
                ]
            );
        }
    }
}
