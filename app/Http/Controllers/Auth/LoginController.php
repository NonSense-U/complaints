<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\Auth\LoginService;
use App\Http\Resources\UserResource;

class LoginController extends Controller
{
    public function __construct(
        protected LoginService $loginService
    ) {}

    public function login(LoginRequest $request)
    {

        $source = $request->header('X-Client', 'mobile');


        $result = $this->loginService->login(
            $request->identifier,
            $request->password,
            $source
        );

        if (isset($result['error'])) {
            return response()->json(['message' => $result['error']], 422);
        }

        return response()->json([
            'message' => 'تم تسجيل الدخول بنجاح',
            'user' => new UserResource($result['user']),
            'token' => $result['token']
        ]);
    }
}
