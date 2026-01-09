<?php

namespace App\Http\Requests\Complaint;

use Illuminate\Foundation\Http\FormRequest;

class ComplaintRequest extends FormRequest
{
    public function authorize()
    {
        // يفترض المصادقة عبر Sanctum / auth:api
        return $this->user() !== null;
    }

    public function rules()
    {
        return [
            'type' => 'required|string',
            'gov_id' => 'required|exists:govs,id',
            'location' => 'required|string',
            'body' => 'required|string|min:10',

            // الصور
            'images' => 'nullable|array|max:4',
            'images.*' => 'required|file|mimes:jpg,jpeg,png|max:10240',

            // المستندات
            'documents' => 'nullable|array|max:2',
            'documents.*' => 'required|file|mimes:pdf,txt|max:10240',
        ];
    }
}
