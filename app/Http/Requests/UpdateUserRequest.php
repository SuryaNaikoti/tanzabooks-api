<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'user_name' => 'required|string',
            'mobile' => 'required',
            'email' => 'required|email',
            'institute_name' => 'required|string'
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
