<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PetResource;
use App\Models\Pet;
use Illuminate\Http\Request;

class SavedPetController extends Controller
{
    /**
     * Get a list of the authenticated user's saved pets.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        // We load the same relationships as the PetController to reuse the PetResource
        $savedPets = $user->savedPets()
            ->with(['primaryImage', 'images', 'organization'])
            ->orderByPivot('created_at', 'desc')
            ->paginate(min((int) $request->get('per_page', 15), 50));

        return PetResource::collection($savedPets);
    }

    /**
     * Toggle the saved status of a pet for the authenticated user.
     */
    public function toggle(Request $request, Pet $pet)
    {
        $user = $request->user();

        // Check if the pet is already saved
        $isSaved = $user->savedPets()->where('pet_id', $pet->id)->exists();

        if ($isSaved) {
            // Unsave the pet
            $user->savedPets()->detach($pet->id);
            $message = 'Pet removed from saved list.';
            $saved = false;
        } else {
            // Save the pet
            $user->savedPets()->attach($pet->id);
            $message = 'Pet saved successfully.';
            $saved = true;
        }

        return response()->json([
            'message' => $message,
            'saved' => $saved,
        ]);
    }
}
