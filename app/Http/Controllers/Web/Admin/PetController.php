<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\TransformsPets;
use App\Models\Pet;

class PetController extends Controller
{
    use TransformsPets;

    public function index()
    {
        $pets = Pet::with(['primaryImage', 'images', 'organization'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn ($pet) => $this->transformPet($pet));

        $canEdit = false;

        return view('shared.pets.index', compact('pets', 'canEdit'));
    }

    public function show(Pet $pet)
    {
        $pet->load(['primaryImage', 'images', 'organization', 'adoptionRequests']);
        $canEdit = false;
        return view('shared.pets.show', compact('pet', 'canEdit'));
    }
}
