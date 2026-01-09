<?php
namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class VerifyOtpRequest extends FormRequest
{
    public function rules()
    {
        return [
            'identifier' => 'required', // email or phone
            'otp' => 'required|digits:6'
        ];
    }
}
