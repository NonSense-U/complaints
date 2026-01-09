<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Models\Complaint;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Broadcasting\PrivateChannel;

class ComplaintStatusUpdated extends Notification implements ShouldBroadcast
{
    use Queueable;

    protected Complaint $complaint;

    public function __construct(Complaint $complaint)
    {
        $this->complaint = $complaint;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    // هذا اللي ينخزن بالداتا بيز + ينرسل ل Flutter
    public function toArray($notifiable)
    {
        return [
            'type' => 'complaint_status_updated',
            'complaint_id' => $this->complaint->id,
            'reference_number' => $this->complaint->reference_number,
            'status' => $this->complaint->status,
            'message' => 'تم تعديل حالة الشكوى',
        ];
    }

    // قناة خاصة لكل مستخدم
    public function broadcastOn()
    {
        return new PrivateChannel('user.' . $this->complaint->user_id);
    }


}
