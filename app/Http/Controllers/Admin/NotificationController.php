<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = auth()->user()
            ->notifications()
            ->latest()
            ->paginate(20);

        return view('admin.notifications.index', compact('notifications'));
    }

    public function markAllRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return back()->with('success', 'All notifications marked as read.');
    }

    public function markRead(string $id)
    {
        $notification = auth()->user()->notifications()->find($id);
        if ($notification) {
            $notification->markAsRead();
        }
        return back();
    }
}
