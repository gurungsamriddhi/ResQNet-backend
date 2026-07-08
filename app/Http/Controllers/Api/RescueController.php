<?php

namespace App\Http\Controllers\Api;

use App\Events\NewRescueReportEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRescueReportRequest;
use App\Models\RescueReport;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Log;

class RescueController extends Controller
{
    public function index(Request $request)
    {
        $reports = RescueReport::with(['user', 'organization', 'assignments.teamMember', 'histories'])
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

    public function show($id)
    {
        // 1. Fetch the report with all necessary relationships
        $report = RescueReport::with([
            'user',
            'organization',
            'assignments.teamMember',
            'histories'
        ])->findOrFail($id);

        // 2. Security Check: 
        // Allow if the current user is the Reporter OR the Organization it belongs to
        $user = auth()->user();

        // Check if user is the reporter OR if the user belongs to the assigned organization
        $isOwner = $report->user_id === $user->id;
        $isOrg = ($user->organization_id && $report->organization_id === $user->organization_id);

        if (!$isOwner && !$isOrg) {
            return response()->json(['message' => 'Unauthorized. You do not have permission to view this report.'], 403);
        }

        return response()->json([
            'data' => $report
        ], 200);
    }

    public function store(StoreRescueReportRequest $request)
    {
        $user = $request->user();

        // Spam Prevention: Check if user has a 'reported' case already
        $hasActiveReport = RescueReport::where('user_id', $user->id)
            ->where('status', 'reported')
            ->exists();

        if ($hasActiveReport) {
            return response()->json([
                'message' => 'You already have an active rescue report pending. Please wait for an organization to claim it.'
            ], 422);
        }

        // Create the report
        $report = RescueReport::create([
            'user_id' => $user->id,
            'title' => $request->title,
            'description' => $request->description,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'address' => $request->address,
            'urgency' => $request->urgency,
            'status' => 'reported',
            'case_code' => 'RQN-' . strtoupper(bin2hex(random_bytes(3))), // Generates unique case code
        ]);

        // Handle Image
        if ($request->hasFile('image')) {
            // Since you aren't using Spatie, manually store the path
            $path = $request->file('image')->store('rescues', 'public');
            $report->update(['image_path' => $path]);
        }

        // Broadcast real-time event so Organization Dashboard updates immediately
        broadcast(new \App\Events\NewRescueReportEvent($report));

        // Send database notification to all active organizations
        $organizations = \App\Models\Organization::where('status', 'active')->get();
        \Illuminate\Support\Facades\Notification::send($organizations, new \App\Notifications\OrganizationAlertNotification(
            'rescue',
            'New Rescue Report',
            "New rescue report submitted: {$report->title}",
            "/organization/rescues/{$report->id}"
        ));

        return response()->json([
            'message' => 'Rescue report submitted successfully!',
            'data' => $report,
        ], 201);
    }

    public function destroy(Request $request, $id)
    {
        $report = RescueReport::findOrFail($id);

        // 1. Security Check: Ensure the user actually owns this report
        if ($report->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized action.'], 403);
        }

        // 2. Logic Check: Only allow cancellation if it hasn't been claimed yet
        if ($report->status !== 'reported') {
            return response()->json([
                'message' => 'You cannot cancel this report because it has already been assigned or is in progress.'
            ], 422);
        }

        // 3. Soft Delete
        $report->delete();

        // 4. Real-time sync (Optional but recommended)
        // Broadcast an event so the org dashboard removes it from their list instantly
        // broadcast(new \App\Events\RescueReportCancelledEvent($report));

        return response()->json([
            'message' => 'Rescue report cancelled successfully.'
        ], 200);
    }
}
