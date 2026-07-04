<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AdoptionRequest;
use Illuminate\Http\Request;

class UserDashboardController extends Controller
{
    public function myAdoptedPets(Request $request)
    {
        // Pull requests that successfully crossed the finish line
        $adoptions = AdoptionRequest::with(['pet.healthRecords', 'pet.vaccinations'])
            ->where('user_id', $request->user()->id)
            ->where('status', 'completed')
            ->get()
            ->pluck('pet'); // Extract just the collection of Pet models

        return response()->json([
            'data' => $adoptions
        ]);
    }
}
