<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AdoptionRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserDashboardController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'user' => $user->only(['id', 'name', 'email', 'phone', 'avatar', 'address', 'fcm_token', 'is_active']),
            'stats' => [
                'total_adoptions' => AdoptionRequest::where('user_id', $user->id)->count(),
                'active_requests' => AdoptionRequest::where('user_id', $user->id)
                    ->whereIn('status', ['pending', 'under_review'])->count(),
                'adopted_pets' => AdoptionRequest::where('user_id', $user->id)
                    ->where('status', 'completed')->count(),
            ],
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'phone' => 'sometimes|string|max:20|unique:users,phone,' . $user->id,
            'address' => 'sometimes|nullable|string|max:500',
            'fcm_token' => 'sometimes|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user->update($validator->validated());

        return response()->json([
            'message' => 'Profile updated successfully.',
            'user' => $user->fresh()->only(['id', 'name', 'email', 'phone', 'avatar', 'address', 'fcm_token']),
        ]);
    }

    public function myAdoptedPets(Request $request)
    {
        $adoptions = AdoptionRequest::with(['pet.healthRecords', 'pet.vaccinations'])
            ->where('user_id', $request->user()->id)
            ->where('status', 'completed')
            ->get()
            ->pluck('pet');

        return response()->json(['data' => $adoptions]);
    }

    /**
     * Lightweight endpoint for mobile apps to push refreshed FCM tokens.
     * Called on app startup and whenever Firebase rotates the device token.
     */
    public function updateFcmToken(Request $request)
    {
        $request->validate(['fcm_token' => 'required|string|max:500']);

        $request->user()->update(['fcm_token' => $request->fcm_token]);

        return response()->json(['message' => 'FCM token updated.']);
    }
}
