<?php

namespace App\Http\Controllers\Web\Organization;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMedicalRecordRequest;
use App\Http\Requests\StoreMedicationRequest;
use App\Http\Requests\StoreVaccinationRequest;
use App\Http\Requests\StoreVetRequest;
use App\Http\Resources\MedicalRecordResource;
use App\Http\Resources\MedicationResource;
use App\Http\Resources\VaccinationResource;
use App\Http\Resources\VetResource;
use App\Models\MedicalRecord;
use App\Models\Medication;
use App\Models\Pet;
use App\Models\Vaccination;
use App\Models\Vet;
use Illuminate\Http\Request;

class HealthController extends Controller
{
    public function index()
    {
        $orgId = auth()->user()?->organization?->id;

        $pets = Pet::with(['medicalRecords', 'vaccinations', 'medications', 'primaryImage'])
            ->where('organization_id', $orgId)
            ->orderBy('name')
            ->get();

        $records = $pets->map(fn ($pet) => $this->transformPetHealth($pet));
        $vets = Vet::where(function ($q) use ($orgId) {
            $q->where('organization_id', $orgId)
              ->orWhereNull('organization_id'); // Include Global Vets
        })->where('is_active', true)->orderBy('name')->get();

        return view('organization.health.index', [
            'records' => $records,
            'vets' => $vets,
            'pets' => $pets->map(fn ($p) => ['id' => $p->id, 'name' => $p->name, 'breed' => $p->breed]),
        ]);
    }

    public function storeMedicalRecord(StoreMedicalRecordRequest $request)
    {
        $this->authorizePet($request->pet_id);
        $record = MedicalRecord::create($request->validated());
        $record->load('vet');

        return response()->json([
            'message' => 'Medical record created.',
            'record' => new MedicalRecordResource($record),
        ], 201);
    }

    public function updateMedicalRecord(Request $request, MedicalRecord $record)
    {
        $this->authorizePet($record->pet_id);
        $request->validate([
            'vet_id' => 'nullable|exists:vets,id',
            'type' => 'sometimes|required|in:checkup,treatment,surgery,injury,observation,other',
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'notes' => 'nullable|string',
            'record_date' => 'sometimes|required|date',
            'next_visit_date' => 'nullable|date',
        ]);
        $record->update($request->only([
            'vet_id', 'type', 'title', 'description', 'notes', 'record_date', 'next_visit_date',
        ]));
        $record->load('vet');

        return response()->json([
            'message' => 'Medical record updated.',
            'record' => new MedicalRecordResource($record),
        ]);
    }

    public function destroyMedicalRecord(MedicalRecord $record)
    {
        $this->authorizePet($record->pet_id);
        $record->delete();

        return response()->json(['message' => 'Medical record deleted.']);
    }

    public function storeVaccination(StoreVaccinationRequest $request)
    {
        $this->authorizePet($request->pet_id);
        $vaccination = Vaccination::create($request->validated());
        $vaccination->load('vet');

        Pet::where('id', $request->pet_id)->update(['is_vaccinated' => true]);

        return response()->json([
            'message' => 'Vaccination recorded.',
            'vaccination' => new VaccinationResource($vaccination),
        ], 201);
    }

    public function destroyVaccination(Vaccination $vaccination)
    {
        $this->authorizePet($vaccination->pet_id);
        $vaccination->delete();

        return response()->json(['message' => 'Vaccination deleted.']);
    }

    public function storeMedication(StoreMedicationRequest $request)
    {
        $this->authorizePet($request->pet_id);
        $medication = Medication::create($request->validated());
        $medication->load('vet');

        return response()->json([
            'message' => 'Medication recorded.',
            'medication' => new MedicationResource($medication),
        ], 201);
    }

    public function destroyMedication(Medication $medication)
    {
        $this->authorizePet($medication->pet_id);
        $medication->delete();

        return response()->json(['message' => 'Medication deleted.']);
    }

    private function authorizePet(int $petId): void
    {
        $orgId = auth()->user()?->organization?->id;
        $pet = Pet::findOrFail($petId);
        if ($pet->organization_id !== $orgId) {
            abort(403);
        }
    }

    private function transformPetHealth(Pet $pet): array
    {
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
