<?php

namespace App\Services\Auth;


use App\Repositories\OtpRepository;
use App\Models\User;
use App\Notifications\SendOtpNotification;
use Illuminate\Support\Facades\Mail;
use App\Mail\RegistrationOtpMail;
use Exception;

class OtpService
{
    public function __construct(
        protected OtpRepository $otpRepo
    ) {}

    public function sendOtp(User $user)
    {
        $otp = rand(100000, 999999);

        //! for testing
        $this->otpRepo->createForUser($user->id, $otp);

        try {
            Mail::to($user->email)->send(
                new RegistrationOtpMail((string) $otp)
            );
        } catch (Exception $e) {
            // ممكن تسجل الخطأ لاحقًا بالـ log
            // Log::error($e->getMessage());
        }


        // إرسال الرمز عبر Notification
        // $user->notify(new SendOtpNotification($otp));
    }

    public function verify(User $user, string $otp)
    {
        $isValid = $this->otpRepo->validateOtp($user->id, $otp);

        if (!$isValid) {
            return false;
        }

        $user->update(['is_verified' => true]);

        return true;
    }

    
}
