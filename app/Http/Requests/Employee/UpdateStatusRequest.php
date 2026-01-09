<?php
namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStatusRequest extends FormRequest
{
    public function authorize() {
        return $this->user() !== null;
    }
    public function rules() {
        return [
            'status' => 'required|in:pending,in_progress,resolved,rejected',
            'note' => 'nullable|string|max:2000'
        ];
    }
}
