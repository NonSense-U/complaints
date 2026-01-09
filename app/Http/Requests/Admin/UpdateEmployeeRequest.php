<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class UpdateEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        // فقط المدير يعدل موظف
        return Auth::check() && Auth::user()->role_id == 1;
    }

    public function rules(): array
    {
        $employeeId = $this->route('id');

        return [
            'name'  => 'nullable|string|max:255',
            'email' => [
                'nullable',
                'email',
                Rule::unique('users', 'email')->ignore($employeeId),
            ],
            'phone' => [
                'nullable',
                'string',
                Rule::unique('users', 'phone')->ignore($employeeId),
            ],
            'NID' => [
                'nullable',
                'string',
                Rule::unique('users', 'NID')->ignore($employeeId),
            ],
            'password' => 'nullable|string|min:8',

            // يسمح بتغيير الجهة الحكومية
            'gov_id' => 'nullable|exists:govs,id',
        ];
    }
}
