<?php
namespace App\Repositories;


use App\Models\Otp;

class OtpRepository
{
    public function createForUser($userId, string $otp)
    {
        return Otp::create([
            'user_id' => $userId,
            'otp' => $otp,
            'expires_at' => now()->addMinutes(5)
        ]);
    }

    public function validateOtp($userId, string $otp)
    {
        return Otp::where('user_id', $userId)
            ->where('otp', $otp)
            ->where('expires_at', '>=', now())
            ->latest()
            ->first();
    }
}