<?php


namespace App\Http\Requests\Complaint;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStatusRequest extends FormRequest
{
    public function rules()
    {
        return [
            'status' => 'required|in:pending,in_progress,resolved,rejected'
        ];
    }
}
