<?php

namespace App\Http\Controllers\Api;

use App\Events\NewRescueReportEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRescueReportRequest;
use App\Models\RescueReport;
use Illuminate\Http\Request;

class RescueController extends Controller
{
    public function index()
    {
        // TODO: Implement index
        return response()->json(['message' => 'Not implemented yet - index']);
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
            'status' => 'pending',
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

    public function show()
    {
        // TODO: Implement show
        return response()->json(['message' => 'Not implemented yet - show']);
    }
}
