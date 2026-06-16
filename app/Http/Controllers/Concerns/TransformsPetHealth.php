<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Pet;

trait TransformsPetHealth
{
    protected function transformPetHealth(Pet $pet): array
    {
        $pet->loadMissing(['medicalRecords', 'vaccinations', 'medications']);

        $lastCheckup = $pet->medicalRecords->first();
        $nextAppointment = $pet->medicalRecords
            ->whereNotNull('next_visit_date')
            ->sortBy('next_visit_date')
            ->first();

        $healthStatus = match (true) {
            $pet->medicalRecords->contains(fn ($r) => in_array($r->type, ['injury', 'surgery', 'treatment'])) => 'Needs Care',
            $pet->vaccinations->isEmpty() => 'Needs Care',
            default => 'Healthy',
        };

        return [
            'id' => $pet->id,
            'name' => $pet->name,
            'breed' => $pet->breed ?? '',
            'qr_code_id' => $pet->qr_code_id,
            'avatarLetter' => strtoupper(substr($pet->name, 0, 1)),
            'status' => $healthStatus,
            'lastCheckup' => $lastCheckup?->record_date?->format('Y-m-d') ?? '',
            'nextAppointment' => $nextAppointment?->next_visit_date?->format('Y-m-d') ?? '',
            'latestMedicalRecordId' => $lastCheckup?->id,
            'nextAppointmentRecordId' => $nextAppointment?->id,
            'vaccinations' => $pet->vaccinations->map(fn ($v) => [
                'id' => $v->id,
                'name' => $v->vaccine_name,
                'status' => true,
                'date' => $v->date_administered?->format('Y-m-d'),
                'next_due' => $v->next_due_date?->format('Y-m-d'),
            ])->values()->toArray(),
            'medications' => $pet->medications->map(fn ($m) => [
                'id' => $m->id,
                'name' => $m->medicine_name,
                'dosage' => $m->dosage,
                'frequency' => $m->frequency,
                'start_date' => $m->start_date?->format('Y-m-d'),
                'end_date' => $m->end_date?->format('Y-m-d'),
            ])->values()->toArray(),
            'history' => $pet->medicalRecords->map(fn ($r) => [
                'id' => $r->id,
                'date' => $r->record_date?->format('Y-m-d'),
                'title' => $r->title,
                'type' => $r->type,
                'notes' => $r->notes ?? $r->description ?? '',
            ])->values()->toArray(),
        ];
    }
}
