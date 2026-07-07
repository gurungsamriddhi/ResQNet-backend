<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserProfileController extends Controller
{
    /**
     * Required fields that must be filled before a user can submit an adoption request.
     */
    private const SCREENING_FIELDS = [
        'housing_type',
        'employment_status',
        'family_members_count',
        'experience_with_pets',
    ];

    /**
     * Get the authenticated user's adoption profile details.
     *
     * The mobile app calls this when the user taps "Adopt Me" to decide
     * whether to show the questionnaire form or the confirmation modal.
     */
    public function show(Request $request)
    {
        $profile = $request->user()->profile;

        if (!$profile) {
            return response()->json([
                'is_profile_complete' => false,
                'profile' => null,
            ], 200);
        }

        // Check that every screening field has a value
        $isComplete = true;
        foreach (self::SCREENING_FIELDS as $field) {
            if (empty($profile->$field)) {
                $isComplete = false;
                break;
            }
        }

        return response()->json([
            'is_profile_complete' => $isComplete,
            'profile' => $profile,
        ], 200);
    }

    /**
     * Create or update the user's adoption profile.
     *
     * Called from the mobile questionnaire form. Once saved, the profile
     * data is permanently stored and auto-pulled on future adoption requests.
     */
    public function storeOrUpdate(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'housing_type'         => 'required|in:apartment,house,condo,other',
            'employment_status'    => 'required|in:employed,unemployed,student,retired',
            'family_members_count' => 'required|integer|min:1',
            'has_other_pets'       => 'required|boolean',
            'experience_with_pets' => 'required|in:none,beginner,intermediate,experienced',
            'has_yard'             => 'required|boolean',
            'is_allergic'          => 'required|boolean',
            'bio'                  => 'nullable|string',
            'occupation'           => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $profile = $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            $validator->validated()
        );

        return response()->json([
            'message' => 'Profile updated successfully!',
            'profile' => $profile,
        ], 200);
    }
}
