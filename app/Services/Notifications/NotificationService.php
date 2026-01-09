<?php

namespace App\Services\Notifications;

use Illuminate\Support\Facades\Auth;
use App\Models\Notification;


class NotificationService
{
    public function getAll(int $userId)
    {
        return Notification::where('notifiable_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function markAsRead(string $notificationId, int $userId): bool
    {
        $notification = Notification::where('id', $notificationId)
            ->where('notifiable_id', $userId)
            ->first();

        if (!$notification) return false;

        $notification->markAsRead(); // ← هاد دالة Laravel الجاهزة
        return true;
    }
}
