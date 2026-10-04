<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['notifications' => []]);
        }

        return response()->json([
            'unread_count' => $user->unreadNotifications->count(),
            'notifications' => $user->notifications()->take(10)->get()
        ]);
    }

    public function markAsRead(Request $request)
    {
        $user = auth()->user();
        if ($request->has('id')) {
            $user->notifications()->where('id', $request->id)->update(['read_at' => now()]);
        } else {
            $user->unreadNotifications->markAsRead();
        }
        return response()->json(['success' => true]);
    }
}
