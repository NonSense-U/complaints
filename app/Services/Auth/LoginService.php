<?php

namespace App\Services\Auth;

use Illuminate\Support\Facades\Hash;
use App\Repositories\UserRepository;

class LoginService
{
    public function __construct(
        protected UserRepository $userRepo
    ) {}

    public function login(string $identifier, string $password, string $source = 'mobile')
    {
        $user = $this->userRepo->findByPhoneOrEmail($identifier);

        if (!$user) {
            return ['error' => 'المستخدم غير موجود'];
        }

        if (!$user->is_verified) {
            return ['error' => 'الحساب غير مفعّل. يرجى إدخال رمز التحقق أولاً.'];
        }

        if (!Hash::check($password, $user->password)) {
            return ['error' => 'كلمة المرور غير صحيحة'];
        }

        if ($source === 'dashboard' && $user->role_id == 3) {
        return ['error' => 'غير مصرح لك بالدخول إلى لوحة التحكم'];
    }
        

        // إصدار التوكن
        $tokenName = $source === 'dashboard' ? 'dashboard_token' : 'mobile_token';
        $token = $user->createToken($tokenName)->plainTextToken;

        return [
            'user' => $user,
            'token' => $token
        ];
    }
}
