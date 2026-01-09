<?php
namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class SendOtpNotification extends Notification
{
    public function __construct(public string $otp) {}

    public function via($notifiable)
    {
        return ['mail']; // أو SMS مستقبلاً
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('رمز التحقق')
            ->line("رمز التحقق الخاص بك هو: {$this->otp}")
            ->line('صالح لمدة 5 دقائق فقط.');
    }
}
