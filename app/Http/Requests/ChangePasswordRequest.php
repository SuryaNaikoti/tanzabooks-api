<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "reset_token" => "required",
            "mobile" => "required",
            "password" => "required|confirmed"
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
