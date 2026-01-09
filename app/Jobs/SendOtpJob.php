<?php

namespace App\Jobs;

use App\Models\User;
use App\Services\Auth\OtpService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendOtpJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */

    public function __construct(public User $user)
    {

    }

    /**
     * Execute the job.
     */
    public function handle(OtpService $otpService): void
    {
        $otpService->sendOtp($this->user);
    }
}
