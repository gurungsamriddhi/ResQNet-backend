<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vet;
use Illuminate\Http\Request;

class VetController extends Controller
{
    public function index()
    {
        $vets = Vet::global()->latest()->paginate(10);
        return view('shared.vets.index', compact('vets'));
    }

    public function create()
    {
        return view('shared.vets.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'specialization' => 'nullable|string|max:255',
            'license_number' => 'nullable|string|max:100',
            'is_active' => 'boolean',
        ]);

        $validated['organization_id'] = null; // Enforce global vet
        $validated['is_active'] = $request->boolean('is_active', true);

        Vet::create($validated);

        return redirect()->route('admin.vets.index')->with('success', 'Global Vet created successfully.');
    }

    public function show(Vet $vet)
    {
        if ($vet->organization_id !== null) {
            abort(403, 'Unauthorized action. This is not a global vet.');
        }
        return view('shared.vets.show', compact('vet'));
    }

    public function edit(Vet $vet)
    {
        if ($vet->organization_id !== null) {
            abort(403, 'Unauthorized action. This is not a global vet.');
        }
        return view('shared.vets.edit', compact('vet'));
    }

    public function update(Request $request, Vet $vet)
    {
        if ($vet->organization_id !== null) {
            abort(403, 'Unauthorized action. This is not a global vet.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'specialization' => 'nullable|string|max:255',
            'license_number' => 'nullable|string|max:100',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        $vet->update($validated);

        return redirect()->route('admin.vets.index')->with('success', 'Global Vet updated successfully.');
    }

    public function destroy(Vet $vet)
    {
        if ($vet->organization_id !== null) {
            abort(403, 'Unauthorized action. This is not a global vet.');
        }

        $vet->delete();

        return redirect()->route('admin.vets.index')->with('success', 'Global Vet deleted successfully.');
    }
}
