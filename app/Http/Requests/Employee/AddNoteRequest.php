<?php
namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;

class AddNoteRequest extends FormRequest
{
    public function authorize() { return $this->user() !== null; }
    public function rules() {
        return ['note' => 'required|string|max:2000'];
    }
}
