<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function rules()
    {
        return [
            'identifier' => 'required|string', // email أو phone
            'password'   => 'required|string'
        ];
    }
}
