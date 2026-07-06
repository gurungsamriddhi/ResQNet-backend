<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\Pet;
use App\Models\RescueReport;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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

        // ── Fix org admin organization_id (migration may have missed them) ──
        foreach (Organization::all() as $org) {
            $admin = User::find($org->user_id);
            if ($admin && !$admin->organization_id) {
                $admin->organization_id = $org->id;
                $admin->save();
            }
        }

        // ── Create team members (rescuers) for each org ──
        $password = Hash::make('12345678');

        $pawsRescuers = [
            ['name' => 'Anisha Gurung', 'email' => 'anisha@pawsandclaws.np'],
            ['name' => 'Bikash Thapa', 'email' => 'bikash@pawsandclaws.np'],
            ['name' => 'Sunita Rai', 'email' => 'sunita@pawsandclaws.np'],
        ];
        $karRescuers = [
            ['name' => 'Rajesh Hamal', 'email' => 'rajesh@kar.np'],
            ['name' => 'Nisha Poudel', 'email' => 'nisha@kar.np'],
        ];

        foreach ($pawsRescuers as $data) {
            User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => $password,
                    'phone' => '+97798' . random_int(10000000, 99999999),
                    'is_active' => true,
                    'email_verified_at' => now(),
                    'organization_id' => $paws->id,
                ]
            );
        }

        foreach ($karRescuers as $data) {
            User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => $password,
                    'phone' => '+97798' . random_int(10000000, 99999999),
                    'is_active' => true,
                    'email_verified_at' => now(),
                    'organization_id' => $kar->id,
                ]
            );
        }

        // ── Rescue reports ──
        $reports = [
            // Existing reports (preserved)
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
            [
                'user_id' => $ramesh->id,
                'organization_id' => $paws->id,
                'pet_id' => null,
                'title' => 'Abandoned puppies near temple',
                'description' => 'Four newborn puppies found in a cardboard box near Bindhyabasini temple.',
                'latitude' => 28.2135,
                'longitude' => 83.9720,
                'address' => 'Bindhyabasini Temple, Pokhara',
                'urgency' => 'critical',
                'status' => 'resolved',
                'resolved_at' => now()->subWeeks(1),
            ],
            [
                'user_id' => $priya->id,
                'organization_id' => $kar->id,
                'pet_id' => null,
                'title' => 'Cat stuck on rooftop',
                'description' => 'A cat has been stuck on a tin roof for two days in the rain.',
                'latitude' => 27.7012,
                'longitude' => 85.3380,
                'address' => 'Gaushala, Kathmandu',
                'urgency' => 'high',
                'status' => 'reported',
            ],
            [
                'user_id' => $sita->id,
                'organization_id' => $kar->id,
                'pet_id' => null,
                'title' => 'Snake removal request',
                'description' => 'Non-venomous snake spotted in residential garden. Residents requesting safe relocation.',
                'latitude' => 27.7250,
                'longitude' => 85.3550,
                'address' => 'Jorpati, Kathmandu',
                'urgency' => 'low',
                'status' => 'resolved',
                'resolved_at' => now()->subDays(10),
            ],

            // ── NEW: Extra reported cases (unclaimed) for "Help Now" testing ──
            [
                'user_id' => $sita->id,
                'organization_id' => null,
                'pet_id' => null,
                'title' => 'Dog hit by vehicle on highway',
                'description' => 'Brown street dog was hit by a speeding vehicle near the highway toll plaza. Bleeding from hind leg.',
                'latitude' => 28.2150,
                'longitude' => 83.9700,
                'address' => 'Highway Toll Plaza, Pokhara',
                'urgency' => 'critical',
                'status' => 'reported',
            ],
            [
                'user_id' => $ramesh->id,
                'organization_id' => null,
                'pet_id' => null,
                'title' => 'Cow stuck in ditch',
                'description' => 'A cow has fallen into an open drainage ditch near the vegetable market. Needs lifting.',
                'latitude' => 27.7100,
                'longitude' => 85.3300,
                'address' => 'Vegetable Market, Kathmandu',
                'urgency' => 'high',
                'status' => 'reported',
            ],
            [
                'user_id' => $priya->id,
                'organization_id' => null,
                'pet_id' => null,
                'title' => 'Parrot with injured wing',
                'description' => 'Green parrot found on balcony unable to fly. Looks like a wing injury.',
                'latitude' => 28.2070,
                'longitude' => 83.9900,
                'address' => 'Baidam-4, Pokhara',
                'urgency' => 'low',
                'status' => 'reported',
            ],

            // ── NEW: Cases already assigned to orgs for "Assign Team" testing ──
            [
                'user_id' => $sita->id,
                'organization_id' => $paws->id,
                'pet_id' => null,
                'title' => 'Rabbit found in schoolyard',
                'description' => 'Domestic rabbit found hopping around the school playground. Likely escaped from home.',
                'latitude' => 28.2080,
                'longitude' => 83.9800,
                'address' => 'Shishuwa School, Pokhara',
                'urgency' => 'low',
                'status' => 'assigned',
            ],
            [
                'user_id' => $ramesh->id,
                'organization_id' => $kar->id,
                'pet_id' => null,
                'title' => 'Monkey causing disturbance',
                'description' => 'Rhesus monkey entered residential area, snatching food and scaring children.',
                'latitude' => 27.6980,
                'longitude' => 85.3480,
                'address' => 'New Baneshwor, Kathmandu',
                'urgency' => 'medium',
                'status' => 'assigned',
            ],

            // ── NEW: Cases in progress for "Mark Complete" testing ──
            [
                'user_id' => $priya->id,
                'organization_id' => $paws->id,
                'pet_id' => null,
                'title' => 'Dehydrated calf near riverbank',
                'description' => 'Young calf collapsed near the riverbank. Seems dehydrated and weak.',
                'latitude' => 28.2120,
                'longitude' => 83.9620,
                'address' => 'Seti Riverbank, Pokhara',
                'urgency' => 'high',
                'status' => 'in_progress',
            ],
            [
                'user_id' => $sita->id,
                'organization_id' => $kar->id,
                'pet_id' => null,
                'title' => 'Burning garbage harming strays',
                'description' => 'Trash fire in an empty lot where stray dogs usually rest. Some dogs have singed fur.',
                'latitude' => 27.7050,
                'longitude' => 85.3350,
                'address' => 'Shantinagar, Kathmandu',
                'urgency' => 'critical',
                'status' => 'in_progress',
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
