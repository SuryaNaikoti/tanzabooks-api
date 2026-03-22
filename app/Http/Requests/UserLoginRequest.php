<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserLoginRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'mobile' => 'required',
            'password' => 'required'
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
