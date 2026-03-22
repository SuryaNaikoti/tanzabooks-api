<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VerifyMobileOtpRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'mobile' => 'required|exists:users',
            'otp' => 'required'
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
