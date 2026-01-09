<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        // فقط المدير يسمح له إنشاء موظف
        return Auth::check() && Auth::user()->role_id == 1;
    }

    public function rules(): array
    {
        return [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'phone'    => 'required|string|unique:users,phone',
            'password' => 'required|string|min:8',
            'NID'      => 'required|string|unique:users,NID',

            // الموظف يجب أن ينتمي لجهة حكومية
            'gov_id'   => 'required|exists:govs,id',
        ];
    }
}
