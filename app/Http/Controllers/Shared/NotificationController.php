<?php

namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Mark a single notification as read and redirect to its URL.
     */
    public function open(string $id): RedirectResponse
    {
        $notification = auth()->user()
            ->notifications()
            ->findOrFail($id);

        $notification->markAsRead();

        $url = $notification->data['url'] ?? null;

        if (! $url || ! str_starts_with($url, '/')) {
            return back();
        }

        return redirect($url);
    }

    /**
     * Mark all unread notifications as read.
     */
    public function readAll(Request $request): RedirectResponse
    {
        auth()->user()->unreadNotifications->markAsRead();

        return back();
    }
}
