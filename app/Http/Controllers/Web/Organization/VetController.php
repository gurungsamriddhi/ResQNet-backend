<?php

namespace App\Http\Controllers\Web\Organization;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVetRequest;
use App\Http\Resources\VetResource;
use App\Models\Vet;
use Illuminate\Http\Request;

class VetController extends Controller
{
    public function index()
    {
        $orgId = auth()->user()?->organization?->id;
        $vets = Vet::organization($orgId)->orderBy('name')->get();

        return response()->json([
            'vets' => VetResource::collection($vets)
        ]);
    }

    public function store(StoreVetRequest $request)
    {
        $orgId = auth()->user()?->organization?->id;
        $vet = Vet::create([
            ...$request->validated(),
            'organization_id' => $orgId,
        ]);

        return response()->json([
            'message' => 'Vet added.',
            'vet' => new VetResource($vet),
        ], 201);
    }

    public function update(Request $request, Vet $vet)
    {
        $orgId = auth()->user()?->organization?->id;

        if ($vet->organization_id !== $orgId) {
            abort(403, 'Unauthorized action. You can only update your organization\'s vets.');
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'specialization' => 'nullable|string|max:255',
            'license_number' => 'nullable|string|max:100',
            'is_active' => 'boolean',
        ]);

        if ($request->has('is_active')) {
            $validated['is_active'] = $request->boolean('is_active');
        }

        $vet->update($validated);

        return response()->json([
            'message' => 'Vet updated.',
            'vet' => new VetResource($vet),
        ]);
    }

    public function destroy(Vet $vet)
    {
        $orgId = auth()->user()?->organization?->id;

        if ($vet->organization_id !== $orgId) {
            abort(403, 'Unauthorized action. You can only delete your organization\'s vets.');
        }

        $vet->delete();

        return response()->json(['message' => 'Vet removed.']);
    }
}
