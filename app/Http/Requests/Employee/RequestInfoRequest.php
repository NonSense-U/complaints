<?php
namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;

class RequestInfoRequest extends FormRequest
{
    public function authorize() { return $this->user() !== null; }
    public function rules() {
        return ['message' => 'required|string|max:1500'];
    }
}
