<?php

namespace App\Http\Controllers\Web\Organization;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $organization = $request->user()->organization;

        if (!$organization) {
            return response()->json([], 404);
        }

        // Fetch up to 10 unread notifications
        $notifications = $organization->unreadNotifications()
            ->latest()
            ->take(10)
            ->get()
            ->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'type' => $notification->data['type'] ?? 'system',
                    'title' => $notification->data['title'] ?? 'Notification',
                    'message' => $notification->data['message'] ?? '',
                    'action_url' => $notification->data['action_url'] ?? '#',
                    'time' => $notification->created_at->diffForHumans(),
                ];
            });

        return response()->json($notifications);
    }

    public function markAsRead(Request $request, $id)
    {
        $organization = $request->user()->organization;

        if ($organization) {
            $notification = $organization->unreadNotifications()->where('id', $id)->first();
            if ($notification) {
                $notification->markAsRead();
            }
        }

        return response()->json(['success' => true]);
    }

    public function markAllAsRead(Request $request)
    {
        $organization = $request->user()->organization;

        if ($organization) {
            $organization->unreadNotifications->markAsRead();
        }

        return response()->json(['success' => true]);
    }
}
