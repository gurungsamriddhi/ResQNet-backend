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

    // app/Http/Controllers/Api/RescueController.php

    public function store(StoreRescueReportRequest $request)
    {
        // // Debugging: Log what is coming in to see the exact format
        // Print everything to storage/logs/laravel.log
        Log::info('POSTMAN DEBUG:', [
            'data' => $request->all(),
            'files' => $request->allFiles()
        ]);
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
}
