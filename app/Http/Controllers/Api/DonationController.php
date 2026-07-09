<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDonationRequest;
use App\Http\Resources\DonationResource;
use App\Models\Donation;
use Illuminate\Http\JsonResponse;

class DonationController extends Controller
{
    public function index(): JsonResponse
    {
        $donations = Donation::query()
            ->with(['user', 'organization'])
            ->latest()
            ->paginate(20);

        return response()->json([
            'data' => DonationResource::collection($donations),
            'meta' => [
                'current_page' => $donations->currentPage(),
                'last_page' => $donations->lastPage(),
                'total' => $donations->total(),
            ],
        ]);
    }

    public function store(StoreDonationRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $isAnonymous = $request->boolean('is_anonymous');

        $donation = Donation::create([
            'user_id' => auth()->id(),
            'organization_id' => $validated['organization_id'] ?? null,
            'donor_name' => $isAnonymous ? null : ($validated['donor_name'] ?? null),
            'donor_email' => $isAnonymous ? null : ($validated['donor_email'] ?? null),
            'donor_phone' => $isAnonymous ? null : ($validated['donor_phone'] ?? null),
            'is_anonymous' => $isAnonymous,
            'frequency' => $validated['frequency'],
            'amount' => $validated['amount'],
            'payment_method' => $validated['payment_method'],
            'status' => 'pending',
            'remarks' => $validated['remarks'] ?? null,
        ]);

        return response()->json([
            'message' => 'Donation recorded successfully.',
            'donation' => new DonationResource($donation),
        ], 201);
    }
}
