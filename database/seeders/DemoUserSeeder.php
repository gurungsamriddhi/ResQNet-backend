<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoUserSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('12345678');

        $orgUsers = [
            ['name' => 'Paws & Claws Admin', 'email' => 'org1@resqnet.com', 'phone' => '+9779841111111', 'role' => 'organization'],
            ['name' => 'Kathmandu Rescue Admin', 'email' => 'org2@resqnet.com', 'phone' => '+9779842222222', 'role' => 'organization'],
        ];

        foreach ($orgUsers as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'phone' => $data['phone'],
                    'password' => $password,
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]
            );

            if (!$user->hasRole($data['role'])) {
                $user->assignRole($data['role']);
            }
        }

        $publicUsers = [
            ['name' => 'Sita Shrestha', 'email' => 'sita@example.com', 'phone' => '+9779841234567'],
            ['name' => 'Ramesh Adhikari', 'email' => 'ramesh@example.com', 'phone' => '+9779859876543'],
            ['name' => 'Priya Tamang', 'email' => 'priya@example.com', 'phone' => '+9779865432109'],
        ];

        foreach ($publicUsers as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'phone' => $data['phone'],
                    'password' => $password,
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]
            );

            if (!$user->hasRole('public_user')) {
                $user->assignRole('public_user');
            }
        }
    }
}
