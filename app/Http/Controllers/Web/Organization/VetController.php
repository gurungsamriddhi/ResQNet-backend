<?php

namespace App\Http\Controllers\Web\Organization;

use App\Http\Controllers\Controller;
use App\Models\Vet;
use Illuminate\Http\Request;

class VetController extends Controller
{
    protected string $routePrefix = 'organization.vets.';

    public function index()
    {
        $this->authorize('viewAny', Vet::class);
        $orgId = auth()->user()?->organization?->id;
        
        $vets = Vet::ownedByOrganization($orgId)->latest()->paginate(10);
        $globalVets = Vet::global()->latest()->get();
        
        return view('shared.vets.index', [
            'vets' => $vets,
            'globalVets' => $globalVets,
            'routePrefix' => $this->routePrefix,
        ]);
    }

    public function create()
    {
        $this->authorize('create', Vet::class);
        return view('shared.vets.create', [
            'routePrefix' => $this->routePrefix,
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('create', Vet::class);
        $orgId = auth()->user()?->organization?->id;

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
            'specialization' => 'nullable|string|max:255',
            'license_number' => 'nullable|string|max:100',
            'status' => 'required|in:active,inactive',
        ], [
            'name.required' => 'The veterinarian\'s full name is required.',
            'name.max' => 'The name must not exceed 255 characters.',
            'email.email' => 'Please provide a valid email address.',
            'email.max' => 'The email must not exceed 255 characters.',
            'phone.max' => 'The phone number must not exceed 20 characters.',
        ]);

        $validated['organization_id'] = $orgId;
        $validated['is_global'] = false;

        $vet = Vet::create($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Vet added successfully.',
                'vet' => $vet
            ], 201);
        }

        return redirect()->route($this->routePrefix . 'index')->with('success', 'Vet added successfully.');
    }

    public function show(Vet $vet)
    {
        $this->authorize('view', $vet);

        if (request()->ajax()) {
            return response()->json([
                'id' => $vet->id,
                'name' => $vet->name,
                'email' => $vet->email,
                'phone' => $vet->phone,
                'address' => $vet->address,
                'specialization' => $vet->specialization,
                'license_number' => $vet->license_number,
                'status' => $vet->status,
                'is_global' => $vet->is_global,
                'created_at' => $vet->created_at->format('M d, Y'),
                'updated_at' => $vet->updated_at->format('M d, Y'),
                'medical_records_count' => $vet->medicalRecords()->count(),
                'vaccinations_count' => $vet->vaccinations()->count(),
                'medications_count' => $vet->medications()->count(),
            ]);
        }

        return view('shared.vets.show', [
            'vet' => $vet,
            'routePrefix' => $this->routePrefix,
        ]);
    }

    public function edit(Vet $vet)
    {
        $this->authorize('update', $vet);

        if (request()->ajax()) {
            return response()->json([
                'id' => $vet->id,
                'name' => $vet->name,
                'email' => $vet->email,
                'phone' => $vet->phone,
                'address' => $vet->address,
                'specialization' => $vet->specialization,
                'license_number' => $vet->license_number,
                'status' => $vet->status,
            ]);
        }

        return view('shared.vets.edit', [
            'vet' => $vet,
            'routePrefix' => $this->routePrefix,
        ]);
    }

    public function update(Request $request, Vet $vet)
    {
        $this->authorize('update', $vet);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
            'specialization' => 'nullable|string|max:255',
            'license_number' => 'nullable|string|max:100',
            'status' => 'required|in:active,inactive',
        ], [
            'name.required' => 'The veterinarian\'s full name is required.',
            'name.max' => 'The name must not exceed 255 characters.',
            'email.email' => 'Please provide a valid email address.',
            'email.max' => 'The email must not exceed 255 characters.',
            'phone.max' => 'The phone number must not exceed 20 characters.',
        ]);

        $vet->update($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Vet updated successfully.',
                'vet' => [
                    'id' => $vet->id,
                    'name' => $vet->name,
                    'email' => $vet->email,
                    'phone' => $vet->phone,
                    'address' => $vet->address,
                    'specialization' => $vet->specialization,
                    'license_number' => $vet->license_number,
                    'status' => $vet->status,
                ],
            ]);
        }

        return redirect()->route($this->routePrefix . 'index')->with('success', 'Vet updated successfully.');
    }

    public function destroy(Vet $vet)
    {
        $this->authorize('delete', $vet);

        $vet->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Vet deleted successfully.',
            ]);
        }

        return redirect()->route($this->routePrefix . 'index')->with('success', 'Vet deleted successfully.');
    }
}
