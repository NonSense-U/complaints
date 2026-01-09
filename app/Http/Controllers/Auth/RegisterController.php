<?php


namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Repositories\UserRepository;
use App\Services\Auth\RegisterService;
use App\Services\Auth\OtpService;
use App\Http\Requests\Auth\RegisterRequest;
use App\Repositories\OtpRepository;

class RegisterController extends Controller
{
    public function __construct(
        public RegisterService $registerService,
        public UserRepository $userRepo,
        public OtpRepository $otpRepo

    ) {}

    public function register(RegisterRequest $request)
    {
        $user = $this->registerService->register($request->validated());
        return response()->json([
            'message' => 'تم إنشاء الحساب، يرجى إدخال رمز التحقق.',
            'user' => new UserResource($user),
            ]
        , 200
        );
    }
}
