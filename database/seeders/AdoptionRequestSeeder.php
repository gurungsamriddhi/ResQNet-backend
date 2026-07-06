<?php

namespace Database\Seeders;

use App\Models\AdoptionRequest;
use App\Models\Pet;
use App\Models\User;
use Illuminate\Database\Seeder;

class AdoptionRequestSeeder extends Seeder
{
    public function run(): void
    {
        $max = Pet::where('qr_code_id', 'RQN-00001')->firstOrFail();
        $luna = Pet::where('qr_code_id', 'RQN-00002')->firstOrFail();
        $rocky = Pet::where('qr_code_id', 'RQN-00004')->firstOrFail();

        $sita = User::where('email', 'sita@example.com')->firstOrFail();
        $ramesh = User::where('email', 'ramesh@example.com')->firstOrFail();
        $priya = User::where('email', 'priya@example.com')->firstOrFail();
        $orgAdmin = User::where('email', 'org1@resqnet.com')->firstOrFail();

        $bella = Pet::where('qr_code_id', 'RQN-00005')->firstOrFail();
        $charlie = Pet::where('qr_code_id', 'RQN-00006')->firstOrFail();
        $orgAdmin = User::where('email', 'org1@resqnet.com')->firstOrFail();
        $org2Admin = User::where('email', 'org2@resqnet.com')->firstOrFail();

        $requests = [
            [
                'pet_id' => $max->id,
                'user_id' => $sita->id,
                'message' => 'I have a spacious home with a fenced yard and I work from home, so I can give Max all the attention he needs.',
                'living_situation' => 'House with fenced yard, Lakeside-6, Pokhara',
                'status' => 'under_review',
            ],
            [
                'pet_id' => $luna->id,
                'user_id' => $ramesh->id,
                'message' => 'Looking for a calm companion for my elderly mother. Luna seems like the perfect fit.',
                'living_situation' => 'Apartment, New Baneshwor-4, Kathmandu',
                'status' => 'pending',
            ],
            [
                'pet_id' => $rocky->id,
                'user_id' => $priya->id,
                'message' => 'We are a family of four looking to adopt our first pet. Rocky\'s playful nature won our hearts.',
                'living_situation' => 'House with garden, Sauraha-2, Chitwan',
                'status' => 'pending',
            ],
            [
                'pet_id' => $max->id,
                'user_id' => $priya->id,
                'message' => 'Previously interested in Rocky but also open to Max if available.',
                'living_situation' => 'House with garden, Sauraha-2, Chitwan',
                'status' => 'rejected',
                'organization_response' => 'Applicant chose another pet.',
                'reviewed_by' => $orgAdmin->id,
                'reviewed_at' => now()->subDays(5),
            ],
            [
                'pet_id' => $bella->id,
                'user_id' => $sita->id,
                'message' => 'We would love to give Bella a calm forever home. We have experience with senior cats.',
                'living_situation' => 'House with garden, Lakeside-6, Pokhara',
                'status' => 'completed',
                'organization_response' => 'Approved — adoption finalized.',
                'reviewed_by' => $org2Admin->id,
                'reviewed_at' => now()->subWeeks(3),
                'response_sent_at' => now()->subWeeks(3),
                'adopted_at' => now()->subWeeks(2),
            ],
            [
                'pet_id' => $charlie->id,
                'user_id' => $ramesh->id,
                'message' => 'Our family has experience with rabbits and a spacious outdoor hutch ready.',
                'living_situation' => 'House with backyard, New Baneshwor-4, Kathmandu',
                'status' => 'approved',
                'organization_response' => 'Approved — please visit the shelter to complete paperwork.',
                'reviewed_by' => $org2Admin->id,
                'reviewed_at' => now()->subDays(1),
                'response_sent_at' => now()->subDays(1),
            ],
        ];

        foreach ($requests as $request) {
            AdoptionRequest::firstOrCreate(
                [
                    'pet_id' => $request['pet_id'],
                    'user_id' => $request['user_id'],
                ],
                $request
            );
        }
    }
}
