<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class NotificationController extends Controller
{
    private function user()
    {
        return JWTAuth::parseToken()->authenticate();
    }

    // GET /api/notifications
    public function index(Request $request)
    {
        $notifications = $this->user()
            ->notifications()
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $notifications,
            'unread_count' => $this->user()->unreadNotifications()->count(),
        ]);
    }

    // GET /api/notifications/unread-count
    public function unreadCount()
    {
        return response()->json([
            'success' => true,
            'unread_count' => $this->user()->unreadNotifications()->count(),
        ]);
    }

    // POST /api/notifications/{id}/read
    public function markAsRead(Notification $notification)
    {
        if ($notification->user_id !== $this->user()->id) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak'], 403);
        }

        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    // POST /api/notifications/read-all
    public function markAllAsRead()
    {
        $this->user()->unreadNotifications()->update([
            'is_read' => true,
            'read_at' => now(),
        ]);

        return response()->json(['success' => true]);
    }

    // DELETE /api/notifications/{id}
    public function destroy(Notification $notification)
    {
        if ($notification->user_id !== $this->user()->id) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak'], 403);
        }

        $notification->delete();

        return response()->json(['success' => true]);
    }
}