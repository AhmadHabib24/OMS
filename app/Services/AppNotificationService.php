<?php

namespace App\Services;

use App\Models\AppNotification;
use App\Models\User;

class AppNotificationService
{
    public function create(
        int $userId,
        string $type,
        string $title,
        ?string $message = null,
        ?string $actionUrl = null,
        array $meta = []
    ): AppNotification {
        return AppNotification::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'action_url' => $actionUrl,
            'meta' => $meta,
        ]);
    }

    public function notifyUsers(
        array $userIds,
        string $type,
        string $title,
        ?string $message = null,
        ?string $actionUrl = null,
        array $meta = []
    ): void {
        foreach (array_unique($userIds) as $userId) {
            $this->create($userId, $type, $title, $message, $actionUrl, $meta);
        }
    }

    public function notifyAdmins(
        string $type,
        string $title,
        ?string $message = null,
        ?string $actionUrl = null,
        array $meta = []
    ): void {
        $adminIds = User::role('admin')->pluck('id')->toArray();

        $this->notifyUsers($adminIds, $type, $title, $message, $actionUrl, $meta);
    }

    public function markAsRead(AppNotification $notification): void
    {
        if (is_null($notification->read_at)) {
            $notification->update([
                'read_at' => now(),
            ]);
        }
    }

    public function markAllAsReadForUser(int $userId): void
    {
        AppNotification::where('user_id', $userId)
            ->whereNull('read_at')
            ->update([
                'read_at' => now(),
            ]);
    }
}