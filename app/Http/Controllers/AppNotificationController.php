<?php

namespace App\Http\Controllers;

use App\Models\AppNotification;
use App\Services\AppNotificationService;
use Illuminate\Http\Request;

class AppNotificationController extends Controller
{
    protected AppNotificationService $notificationService;

    public function __construct(AppNotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function index()
    {
        abort_unless(auth()->user()->can('view notifications'), 403);
        $notifications = auth()->user()
            ->appNotifications()
            ->paginate(15);

        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead(AppNotification $notification)
    {

        abort_unless(auth()->user()->can('mark notifications as read'), 403);
        if ($notification->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }

        $this->notificationService->markAsRead($notification);

        if ($notification->action_url) {
            return redirect($notification->action_url);
        }

        return redirect()->route('notifications.show', $notification);
    }

    public function markAllAsRead()
    {
        abort_unless(auth()->user()->can('mark notifications as read'), 403);
        $this->notificationService->markAllAsReadForUser(auth()->id());

        return back()->with('success', 'All notifications marked as read.');
    }
    public function show(AppNotification $notification)
    {
        abort_unless(auth()->user()->can('view notifications'), 403);
        if ($notification->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }

        $this->notificationService->markAsRead($notification);

        return view('notifications.show', compact('notification'));
    }
    public function quickRead(AppNotification $notification)
    {
        abort_unless(auth()->user()->can('mark notifications as read'), 403);
        if ($notification->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }

        $this->notificationService->markAsRead($notification);

        return back()->with('success', 'Notification marked as read.');
    }
}