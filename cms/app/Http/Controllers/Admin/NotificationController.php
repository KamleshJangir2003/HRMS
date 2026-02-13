<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function getUnread()
    {
        $notifications = Notification::unread()->latest()->take(10)->get();
        $count = Notification::unread()->count();
        
        return response()->json([
            'notifications' => $notifications,
            'count' => $count
        ]);
    }

    public function markAsRead($id)
    {
        Notification::find($id)->update(['is_read' => true]);
        return response()->json(['success' => true]);
    }

    public function markAllAsRead()
    {
        Notification::unread()->update(['is_read' => true]);
        return response()->json(['success' => true]);
    }
}