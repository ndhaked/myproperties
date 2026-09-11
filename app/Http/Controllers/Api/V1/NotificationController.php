<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Models\AppNotification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $notifications = $request->user()->appNotifications()
            ->latest()
            ->paginate((int) $request->input('limit', 10));

        return ApiResponse::success(ApiResponse::paginated($notifications, fn ($notification) => [
            'id' => 'notif_' . $notification->id,
            'title' => $notification->title,
            'body' => $notification->body,
            'isRead' => (bool) $notification->is_read,
            'createdAt' => $notification->created_at->toIso8601String(),
        ]));
    }

    public function unreadCount(Request $request)
    {
        return ApiResponse::success([
            'count' => $request->user()->appNotifications()->where('is_read', false)->count(),
        ]);
    }

    public function markRead(Request $request, AppNotification $notification)
    {
        abort_if($notification->user_id !== $request->user()->id, 403);

        $notification->update(['is_read' => true]);

        return ApiResponse::success(null, 'Marked as read.');
    }
}
