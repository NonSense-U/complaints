<?php

namespace App\Services\Auth;

use App\Jobs\SendOtpJob;
use App\Repositories\UserRepository;
class RegisterService
{
    public function __construct(
        protected UserRepository $userRepo,
        protected OtpService $otpService
    ) {}

    public function register(array $data)
    {
        $data['role_id'] = 3; // Citizen
        $data['gov_id'] = null;
        $user = $this->userRepo->create($data);

        SendOtpJob::dispatch($user);
        return $user;
    }
}