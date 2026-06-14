<?php

namespace Database\Seeders;

use App\Models\MedicalRecord;
use App\Models\Medication;
use App\Models\Pet;
use App\Models\Vaccination;
use App\Models\Vet;
use Illuminate\Database\Seeder;

class PetHealthSeeder extends Seeder
{
    public function run(): void
    {
        $max = Pet::where('qr_code_id', 'RQN-00001')->firstOrFail();
        $luna = Pet::where('qr_code_id', 'RQN-00002')->firstOrFail();
        $rocky = Pet::where('qr_code_id', 'RQN-00004')->firstOrFail();

        $anisha = Vet::where('license_number', 'VET-NP-10234')->firstOrFail();
        $bikash = Vet::where('license_number', 'VET-NP-10567')->firstOrFail();
        $meera = Vet::where('license_number', 'VET-NP-10891')->firstOrFail();

        $records = [
            [
                'pet_id' => $max->id,
                'vet_id' => $anisha->id,
                'type' => 'checkup',
                'title' => 'Routine Checkup',
                'description' => 'Overall healthy condition. Weight: 28kg.',
                'notes' => 'No concerns. Continue regular exercise.',
                'record_date' => '2026-05-10',
                'next_visit_date' => '2026-07-15',
            ],
            [
                'pet_id' => $max->id,
                'vet_id' => $anisha->id,
                'type' => 'treatment',
                'title' => 'Minor Treatment',
                'description' => 'Treated for mild ear infection.',
                'notes' => 'Prescribed ear drops for 7 days.',
                'record_date' => '2025-11-05',
                'next_visit_date' => null,
            ],
            [
                'pet_id' => $luna->id,
                'vet_id' => $bikash->id,
                'type' => 'checkup',
                'title' => 'Dental Assessment',
                'description' => 'Maxillary dental assessment. Needs cleaning.',
                'notes' => 'Slightly underweight at 16kg.',
                'record_date' => '2026-04-22',
                'next_visit_date' => '2026-06-12',
            ],
            [
                'pet_id' => $rocky->id,
                'vet_id' => $meera->id,
                'type' => 'checkup',
                'title' => 'Semi-annual Wellness Exam',
                'description' => 'Excellent health. Coat shiny, teeth clean.',
                'notes' => 'Weight: 14kg.',
                'record_date' => '2026-06-01',
                'next_visit_date' => '2026-08-01',
            ],
        ];

        foreach ($records as $record) {
            MedicalRecord::firstOrCreate(
                [
                    'pet_id' => $record['pet_id'],
                    'title' => $record['title'],
                    'record_date' => $record['record_date'],
                ],
                $record
            );
        }

        $vaccinations = [
            [
                'pet_id' => $max->id,
                'vet_id' => $anisha->id,
                'vaccine_name' => 'Rabies',
                'batch_number' => 'RB-2026-001',
                'date_administered' => '2026-02-12',
                'next_due_date' => '2027-02-12',
                'notes' => 'Annual booster.',
            ],
            [
                'pet_id' => $max->id,
                'vet_id' => $anisha->id,
                'vaccine_name' => 'DHPP',
                'batch_number' => 'DH-2026-014',
                'date_administered' => '2026-02-12',
                'next_due_date' => '2027-02-12',
                'notes' => null,
            ],
            [
                'pet_id' => $luna->id,
                'vet_id' => $anisha->id,
                'vaccine_name' => 'Rabies',
                'batch_number' => 'RB-2026-002',
                'date_administered' => '2026-01-15',
                'next_due_date' => '2027-01-15',
                'notes' => null,
            ],
            [
                'pet_id' => $rocky->id,
                'vet_id' => $meera->id,
                'vaccine_name' => 'Rabies',
                'batch_number' => 'RB-2026-003',
                'date_administered' => '2026-03-01',
                'next_due_date' => '2027-03-01',
                'notes' => null,
            ],
            [
                'pet_id' => $rocky->id,
                'vet_id' => $meera->id,
                'vaccine_name' => 'DHPP',
                'batch_number' => 'DH-2026-021',
                'date_administered' => '2026-03-01',
                'next_due_date' => '2027-03-01',
                'notes' => null,
            ],
        ];

        foreach ($vaccinations as $vaccination) {
            Vaccination::firstOrCreate(
                [
                    'pet_id' => $vaccination['pet_id'],
                    'vaccine_name' => $vaccination['vaccine_name'],
                    'date_administered' => $vaccination['date_administered'],
                ],
                $vaccination
            );
        }

        $medications = [
            [
                'pet_id' => $max->id,
                'vet_id' => $anisha->id,
                'medicine_name' => 'Ear Drops',
                'dosage' => '2 drops per ear',
                'frequency' => 'Twice daily',
                'start_date' => '2025-11-05',
                'end_date' => '2025-11-12',
                'notes' => 'Completed course for ear infection.',
            ],
            [
                'pet_id' => $luna->id,
                'vet_id' => $bikash->id,
                'medicine_name' => 'Multivitamin Supplement',
                'dosage' => '1 tablet',
                'frequency' => 'Once daily',
                'start_date' => '2026-04-22',
                'end_date' => '2026-05-22',
                'notes' => 'Support weight gain after dental assessment.',
            ],
        ];

        foreach ($medications as $medication) {
            Medication::firstOrCreate(
                [
                    'pet_id' => $medication['pet_id'],
                    'medicine_name' => $medication['medicine_name'],
                    'start_date' => $medication['start_date'],
                ],
                $medication
            );
        }
    }
}
