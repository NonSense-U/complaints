<?php
namespace App\Http\Controllers\Auth;


use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\Services\Auth\OtpService;
use App\Http\Requests\Auth\VerifyOtpRequest;
class OtpController extends Controller
{
    public function __construct(
        protected UserRepository $userRepo,
        protected OtpService $otpService
    ) {}

    public function verify(VerifyOtpRequest $request)
    {
        $user = $this->userRepo->findByPhoneOrEmail($request->identifier);

        if (!$user) {
            return response()->json(['message' => 'المستخدم غير موجود'], 404);
        }

        if (!$this->otpService->verify($user, $request->otp)) {
            return response()->json(['message' => 'رمز التحقق غير صحيح أو منتهي'], 422);
        }

        return response()->json(['message' => 'تم تفعيل الحساب بنجاح']);
        
    }
}
