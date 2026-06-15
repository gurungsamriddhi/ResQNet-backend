<?php

namespace App\Http\Controllers\Web\Organization;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\TransformsPetHealth;
use App\Http\Requests\StoreMedicalRecordRequest;
use App\Http\Requests\StoreMedicationRequest;
use App\Http\Requests\StoreVaccinationRequest;
use App\Http\Resources\MedicalRecordResource;
use App\Http\Resources\MedicationResource;
use App\Http\Resources\VaccinationResource;
use App\Models\MedicalRecord;
use App\Models\Medication;
use App\Models\Pet;
use App\Models\Vaccination;
use App\Models\Vet;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class HealthController extends Controller
{
    use TransformsPetHealth;

    public function index()
    {
        $orgId = auth()->user()?->organization?->id;

        $medicalRecordsQuery = MedicalRecord::with('pet.primaryImage');
        $vaccinationsQuery = Vaccination::with('pet.primaryImage');
        $petsQuery = Pet::query()->with('primaryImage');
        $vetsQuery = Vet::where('is_active', true);

        if ($orgId) {
            $medicalRecordsQuery->whereHas('pet', fn ($q) => $q->where('organization_id', $orgId));
            $vaccinationsQuery->whereHas('pet', fn ($q) => $q->where('organization_id', $orgId));
            $petsQuery->where('organization_id', $orgId);
            $vetsQuery->where(function ($q) use ($orgId) {
                $q->where('organization_id', $orgId)->orWhereNull('organization_id');
            });
        }

        $medicalRecords = $medicalRecordsQuery->latest('record_date')->get()->map(fn ($r) => [
            'id' => $r->id,
            'pet_id' => $r->pet_id,
            'pet_name' => $r->pet?->name ?? 'Unknown',
            'pet_breed' => $r->pet?->breed ?? '',
            'pet_species' => $r->pet?->species ?? '',
            'pet_image' => $r->pet?->primaryImage?->image_url ?? null,
            'pet_qr' => $r->pet?->qr_code_id ?? '',
            'type_label' => 'Medical Report',
            'type' => $r->type,
            'title' => $r->title,
            'description' => $r->description,
            'notes' => $r->notes,
            'date' => $r->record_date?->format('Y-m-d'),
            'next_date' => $r->next_visit_date?->format('Y-m-d'),
            'vet_name' => $r->vet?->name ?? null,
            'model' => 'medical',
            'created_at' => $r->created_at,
        ]);

        $vaccinations = $vaccinationsQuery->latest('date_administered')->get()->map(fn ($v) => [
            'id' => $v->id,
            'pet_id' => $v->pet_id,
            'pet_name' => $v->pet?->name ?? 'Unknown',
            'pet_breed' => $v->pet?->breed ?? '',
            'pet_species' => $v->pet?->species ?? '',
            'pet_image' => $v->pet?->primaryImage?->image_url ?? null,
            'pet_qr' => $v->pet?->qr_code_id ?? '',
            'type_label' => 'Vaccination',
            'type' => 'vaccine',
            'title' => $v->vaccine_name,
            'description' => null,
            'notes' => $v->notes,
            'date' => $v->date_administered?->format('Y-m-d'),
            'next_date' => $v->next_due_date?->format('Y-m-d'),
            'vet_name' => $v->vet?->name ?? null,
            'model' => 'vaccination',
            'created_at' => $v->created_at,
        ]);

        $healthRecords = $medicalRecords->concat($vaccinations)->sortByDesc('created_at');
        $vets = $vetsQuery->orderBy('name')->get();
        $pets = $petsQuery->orderBy('name')->get(['id', 'name', 'breed', 'species', 'gender', 'date_of_birth', 'color']);
        $pets->each(fn ($pet) => $pet->pet_image = $pet->primaryImage?->image_url);

        return view('organization.health.index', compact('healthRecords', 'vets', 'pets'));
    }

    public function storeMedicalRecord(StoreMedicalRecordRequest $request): JsonResponse|RedirectResponse
    {
        $this->authorizePet($request->pet_id);
        $data = $request->validated();
        $data['vet_id'] = $this->resolveVetId($request);
        $record = MedicalRecord::create($data);
        $record->load('vet');

        return $this->healthActionResponse(
            $request,
            'Medical record added successfully.',
            ['record' => new MedicalRecordResource($record)],
            201
        );
    }

    public function updateMedicalRecord(Request $request, MedicalRecord $record): JsonResponse|RedirectResponse
    {
        $this->authorizePet($record->pet_id);
        $request->validate([
            'vet_id' => 'nullable|exists:vets,id',
            'vet_name' => 'nullable|string|max:255',
            'type' => 'sometimes|required|in:checkup,treatment,surgery,injury,observation,other',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'notes' => 'nullable|string',
            'record_date' => 'sometimes|required|date',
            'next_visit_date' => 'nullable|date',
        ]);
        $data = $request->only([
            'vet_id', 'type', 'title', 'description', 'notes', 'record_date', 'next_visit_date',
        ]);
        $data['vet_id'] = $this->resolveVetId($request);
        $record->update($data);
        $record->load('vet');

        return $this->healthActionResponse(
            $request,
            'Medical record updated successfully.',
            ['record' => new MedicalRecordResource($record)]
        );
    }

    public function destroyMedicalRecord(Request $request, MedicalRecord $record): JsonResponse|RedirectResponse
    {
        $this->authorizePet($record->pet_id);
        $record->delete();

        return $this->healthActionResponse($request, 'Medical record deleted successfully.');
    }

    public function storeVaccination(StoreVaccinationRequest $request): JsonResponse|RedirectResponse
    {
        $this->authorizePet($request->pet_id);
        $data = $request->validated();
        $data['vet_id'] = $this->resolveVetId($request);
        $vaccination = Vaccination::create($data);
        $vaccination->load('vet');

        Pet::where('id', $request->pet_id)->update(['is_vaccinated' => true]);

        return $this->healthActionResponse(
            $request,
            'Vaccination record added successfully.',
            ['vaccination' => new VaccinationResource($vaccination)],
            201
        );
    }

    public function updateVaccination(Request $request, Vaccination $vaccination): JsonResponse|RedirectResponse
    {
        $this->authorizePet($vaccination->pet_id);
        $request->validate([
            'vet_id' => 'nullable|exists:vets,id',
            'vet_name' => 'nullable|string|max:255',
            'vaccine_name' => 'sometimes|required|string|max:255',
            'batch_number' => 'nullable|string|max:100',
            'date_administered' => 'sometimes|required|date',
            'next_due_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);
        $data = $request->only([
            'vet_id', 'vaccine_name', 'batch_number', 'date_administered', 'next_due_date', 'notes',
        ]);
        $data['vet_id'] = $this->resolveVetId($request);
        $vaccination->update($data);
        $vaccination->load('vet');

        return $this->healthActionResponse(
            $request,
            'Vaccination record updated successfully.',
            ['vaccination' => new VaccinationResource($vaccination)]
        );
    }

    public function destroyVaccination(Request $request, Vaccination $vaccination): JsonResponse|RedirectResponse
    {
        $this->authorizePet($vaccination->pet_id);
        $vaccination->delete();

        return $this->healthActionResponse($request, 'Vaccination deleted successfully.');
    }

    public function storeMedication(StoreMedicationRequest $request): JsonResponse|RedirectResponse
    {
        $this->authorizePet($request->pet_id);
        $medication = Medication::create($request->validated());
        $medication->load('vet');

        return $this->healthActionResponse(
            $request,
            'Medication recorded successfully.',
            ['medication' => new MedicationResource($medication)],
            201
        );
    }

    public function destroyMedication(Request $request, Medication $medication): JsonResponse|RedirectResponse
    {
        $this->authorizePet($medication->pet_id);
        $medication->delete();

        return $this->healthActionResponse($request, 'Medication deleted successfully.');
    }

    public function updatePetHealthSummary(Request $request, Pet $pet): JsonResponse|RedirectResponse
    {
        $this->authorizePet($pet->id);

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'breed' => 'nullable|string|max:255',
            'last_checkup' => 'nullable|date',
            'next_appointment' => 'nullable|date',
        ]);

        $pet->update(collect($validated)->only(['name', 'breed'])->filter(fn ($value) => $value !== null)->all());

        $pet->load('medicalRecords');

        if (! empty($validated['last_checkup'])) {
            $latestRecord = $pet->medicalRecords->sortByDesc('record_date')->first();

            if ($latestRecord) {
                $latestRecord->update(['record_date' => $validated['last_checkup']]);
            } else {
                MedicalRecord::create([
                    'pet_id' => $pet->id,
                    'type' => 'checkup',
                    'title' => 'Checkup',
                    'record_date' => $validated['last_checkup'],
                    'next_visit_date' => $validated['next_appointment'] ?? null,
                ]);
            }
        }

        if (! empty($validated['next_appointment'])) {
            $pet->load('medicalRecords');

            $appointmentRecord = $pet->medicalRecords
                ->whereNotNull('next_visit_date')
                ->sortBy('next_visit_date')
                ->first()
                ?? $pet->medicalRecords->sortByDesc('record_date')->first();

            if ($appointmentRecord) {
                $appointmentRecord->update(['next_visit_date' => $validated['next_appointment']]);
            }
        }

        $pet->refresh()->load(['medicalRecords', 'vaccinations', 'medications']);

        return $this->healthActionResponse(
            $request,
            'Health record updated successfully.',
            ['pet' => $this->transformPetHealth($pet)]
        );
    }

    public function clearPetMedicalHistory(Request $request, Pet $pet): JsonResponse|RedirectResponse
    {
        $this->authorizePet($pet->id);
        $pet->medicalRecords()->delete();

        return $this->healthActionResponse($request, 'Medical history cleared successfully.');
    }

    private function resolveVetId(Request $request): ?int
    {
        if ($request->filled('vet_id')) {
            return $request->vet_id;
        }

        if ($request->filled('vet_name')) {
            $vet = Vet::firstOrCreate(
                ['name' => $request->vet_name],
                [
                    'is_active' => true,
                    'organization_id' => auth()->user()?->organization?->id,
                ]
            );
            return $vet->id;
        }

        return null;
    }

    private function authorizePet(int $petId): void
    {
        $orgId = auth()->user()?->organization?->id;
        $pet = Pet::findOrFail($petId);
        if ($orgId && $pet->organization_id !== $orgId) {
            abort(403);
        }
    }

    private function healthActionResponse(
        Request $request,
        string $message,
        array $payload = [],
        int $status = 200
    ): JsonResponse|RedirectResponse {
        if ($this->wantsJsonResponse($request)) {
            return response()->json(array_merge(['message' => $message], $payload), $status);
        }

        return redirect($this->resolveRedirectUrl($request))
            ->with('success', $message);
    }

    private function wantsJsonResponse(Request $request): bool
    {
        return $request->expectsJson()
            || $request->wantsJson()
            || $request->ajax();
    }

    private function resolveRedirectUrl(Request $request): string
    {
        $redirectTo = $request->input('redirect_to');

        if (is_string($redirectTo) && $redirectTo !== '') {
            $appUrl = rtrim((string) config('app.url'), '/');
            if (str_starts_with($redirectTo, '/') || ($appUrl !== '' && str_starts_with($redirectTo, $appUrl))) {
                return $redirectTo;
            }
        }

        $referer = $request->headers->get('referer');
        if (is_string($referer) && $referer !== '') {
            if (!str_contains($referer, '#health-records')) {
                return $referer . '#health-records';
            }
            return $referer;
        }

        return route('organization.health.index');
    }
}
