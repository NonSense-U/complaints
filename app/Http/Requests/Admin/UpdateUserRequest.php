<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        // فقط المدير يعدل موظف
        return Auth::check() && Auth::user()->role_id == 1;
    }

    public function rules(): array
    {
        $userId = $this->route('id');

        return [
            'name'  => 'nullable|string|max:255',
            'email' => [
                'nullable',
                'email',
                Rule::unique('users', 'email')->ignore($userId),
            ],
            'phone' => [
                'nullable',
                'string',
                Rule::unique('users', 'phone')->ignore($userId),
            ],
            'NID' => [
                'nullable',
                'string',
                Rule::unique('users', 'NID')->ignore($userId),
            ],
            'password' => 'nullable|string|min:8',
        ];
    }
}
