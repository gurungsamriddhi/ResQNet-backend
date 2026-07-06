<?php

namespace Database\Seeders;

use App\Models\Donation;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Seeder;

class DonationSeeder extends Seeder
{
    public function run(): void
    {
        $paws = Organization::where('slug', 'paws-and-claws')->firstOrFail();
        $kar = Organization::where('slug', 'kathmandu-animal-rescue')->firstOrFail();

        $sita = User::where('email', 'sita@example.com')->firstOrFail();
        $ramesh = User::where('email', 'ramesh@example.com')->firstOrFail();
        $priya = User::where('email', 'priya@example.com')->firstOrFail();

        $donations = [
            [
                'user_id' => $sita->id,
                'organization_id' => $paws->id,
                'amount' => 500.00,
                'payment_method' => 'khalti',
                'transaction_id' => 'KHL-20260601-001',
                'status' => 'completed',
                'payment_payload' => ['gateway' => 'khalti', 'idx' => 'KHL-20260601-001'],
                'remarks' => 'Monthly shelter support',
            ],
            [
                'user_id' => $ramesh->id,
                'organization_id' => $kar->id,
                'amount' => 1000.00,
                'payment_method' => 'esewa',
                'transaction_id' => 'ESW-20260602-014',
                'status' => 'completed',
                'payment_payload' => ['gateway' => 'esewa', 'ref' => 'ESW-20260602-014'],
                'remarks' => 'Emergency rescue fund',
            ],
            [
                'user_id' => $priya->id,
                'organization_id' => $paws->id,
                'amount' => 250.00,
                'payment_method' => 'khalti',
                'transaction_id' => null,
                'status' => 'pending',
                'payment_payload' => null,
                'remarks' => 'General donation',
            ],
            [
                'user_id' => $sita->id,
                'organization_id' => $kar->id,
                'amount' => 300.00,
                'payment_method' => 'esewa',
                'transaction_id' => 'ESW-20260515-008',
                'status' => 'failed',
                'payment_payload' => ['gateway' => 'esewa', 'error' => 'Insufficient balance'],
                'remarks' => 'Retry later',
            ],
            [
                'user_id' => $ramesh->id,
                'organization_id' => $paws->id,
                'amount' => 2000.00,
                'payment_method' => 'khalti',
                'transaction_id' => 'KHL-20260610-002',
                'status' => 'completed',
                'payment_payload' => ['gateway' => 'khalti', 'idx' => 'KHL-20260610-002'],
                'remarks' => 'Sponsor a pet surgery',
            ],
            [
                'user_id' => $priya->id,
                'organization_id' => $kar->id,
                'amount' => 750.00,
                'payment_method' => 'esewa',
                'transaction_id' => 'ESW-20260615-023',
                'status' => 'completed',
                'payment_payload' => ['gateway' => 'esewa', 'ref' => 'ESW-20260615-023'],
                'remarks' => 'Flood relief for animals',
            ],
            [
                'user_id' => $sita->id,
                'organization_id' => $paws->id,
                'amount' => 150.00,
                'payment_method' => 'khalti',
                'transaction_id' => 'KHL-20260618-004',
                'status' => 'completed',
                'payment_payload' => ['gateway' => 'khalti', 'idx' => 'KHL-20260618-004'],
                'remarks' => 'Treats and toys',
            ],
        ];

        foreach ($donations as $donation) {
            Donation::firstOrCreate(
                [
                    'user_id' => $donation['user_id'],
                    'organization_id' => $donation['organization_id'],
                    'amount' => $donation['amount'],
                    'status' => $donation['status'],
                    'remarks' => $donation['remarks'],
                ],
                $donation
            );
        }
    }
}
