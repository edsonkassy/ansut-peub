<?php

namespace App\Http\Controllers;

use App\Models\SystemNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = SystemNotification::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    public function unread()
    {
        $notifications = SystemNotification::where('user_id', Auth::id())
            ->unread()
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $unreadCount = SystemNotification::where('user_id', Auth::id())
            ->unread()
            ->count();

        return response()->json([
            'notifications' => $notifications->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'type' => $notification->type,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'icon' => $notification->type_icon,
                    'color' => $notification->type_color,
                    'created_at' => $notification->created_at->diffForHumans(),
                    'data' => $notification->data
                ];
            }),
            'unread_count' => $unreadCount
        ]);
    }

    public function markAsRead($id)
    {
        $notification = SystemNotification::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    public function markAllAsRead()
    {
        SystemNotification::where('user_id', Auth::id())
            ->unread()
            ->update([
                'read' => true,
                'read_at' => now()
            ]);

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $notification = SystemNotification::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        $notification->delete();

        return back()->with('success', 'Notification supprimée.');
    }
}