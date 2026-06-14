<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\Pet;
use App\Models\RescueReport;
use App\Models\User;
use Illuminate\Database\Seeder;

class RescueReportSeeder extends Seeder
{
    public function run(): void
    {
        $paws = Organization::where('slug', 'paws-and-claws')->firstOrFail();
        $kar = Organization::where('slug', 'kathmandu-animal-rescue')->firstOrFail();
        $milo = Pet::where('qr_code_id', 'RQN-00003')->firstOrFail();

        $sita = User::where('email', 'sita@example.com')->firstOrFail();
        $ramesh = User::where('email', 'ramesh@example.com')->firstOrFail();
        $priya = User::where('email', 'priya@example.com')->firstOrFail();

        $reports = [
            [
                'user_id' => $sita->id,
                'organization_id' => $paws->id,
                'pet_id' => null,
                'title' => 'Injured stray dog near Lakeside',
                'description' => 'Found a limping brown dog near the lakeside trail. Appears dehydrated.',
                'latitude' => 28.2105,
                'longitude' => 83.9580,
                'address' => 'Lakeside Road, Pokhara',
                'urgency' => 'high',
                'status' => 'assigned',
            ],
            [
                'user_id' => $ramesh->id,
                'organization_id' => $kar->id,
                'pet_id' => null,
                'title' => 'Kitten stuck in drainage',
                'description' => 'Small tabby kitten heard crying from a drainage pipe in Baneshwor.',
                'latitude' => 27.6945,
                'longitude' => 85.3420,
                'address' => 'Baneshwor Chowk, Kathmandu',
                'urgency' => 'critical',
                'status' => 'in_progress',
            ],
            [
                'user_id' => $priya->id,
                'organization_id' => $paws->id,
                'pet_id' => $milo->id,
                'title' => 'Rescued indoor cat — now in care',
                'description' => 'Cat was abandoned near a market. Now registered as Milo at the shelter.',
                'latitude' => 28.2090,
                'longitude' => 83.9860,
                'address' => 'Lakeside-6, Pokhara',
                'urgency' => 'medium',
                'status' => 'resolved',
                'resolved_at' => now()->subWeeks(3),
            ],
            [
                'user_id' => $sita->id,
                'organization_id' => null,
                'pet_id' => null,
                'title' => 'Bird with broken wing',
                'description' => 'Injured bird found in a park. Unable to fly.',
                'latitude' => 27.7172,
                'longitude' => 85.3240,
                'address' => 'Ratna Park, Kathmandu',
                'urgency' => 'medium',
                'status' => 'reported',
            ],
        ];

        foreach ($reports as $report) {
            RescueReport::firstOrCreate(
                [
                    'user_id' => $report['user_id'],
                    'title' => $report['title'],
                ],
                $report
            );
        }
    }
}
