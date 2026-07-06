<?php

namespace App\Http\Controllers\Api;

use App\Events\NewRescueReportEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRescueReportRequest;
use App\Models\RescueReport;
use Illuminate\Http\Request;

class RescueController extends Controller
{
    public function index(Request $request)
    {
        $reports = RescueReport::with(['user', 'organization', 'assignments.user', 'media'])
            ->latest()
            ->paginate($request->per_page ?? 20);

        return response()->json([
            'data' => $reports->items(),
            'meta' => [
                'current_page' => $reports->currentPage(),
                'last_page' => $reports->lastPage(),
                'per_page' => $reports->perPage(),
                'total' => $reports->total(),
            ],
        ]);
    }

    public function store(StoreRescueReportRequest $request)
    {
        $user = $request->user();

        $report = RescueReport::create([
            'user_id' => $user->id,
            'organization_id' => $request->organization_id,
            'title' => $request->title,
            'description' => $request->description,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'address' => $request->address,
            'urgency' => $request->urgency,
            'status' => 'reported',
        ]);

        if ($request->hasFile('image')) {
            $report->addMediaFromRequest('image')->toMediaCollection('rescue_images');
        }

        broadcast(new NewRescueReportEvent($report));

        $report->load('media');

        return response()->json([
            'message' => 'Rescue report submitted successfully!',
            'data' => $report,
        ], 201);
    }

    public function show(RescueReport $report)
    {
        $report->load(['user', 'organization', 'assignments.user', 'histories.changedBy', 'media']);

        return response()->json([
            'data' => $report,
        ]);
    }
}
