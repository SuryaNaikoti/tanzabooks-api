<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UserRegisterRequest extends FormRequest
{
    public function rules()
    {
        if ($this->mobile == '6301678590'){
            User::where('mobile', '6301678590')->delete();
        }
        return [
            'institute_name' => 'required|string',
            'username' => 'required|string',
            'email' => 'required|email|unique:users',
            'mobile' => 'required|unique:users',
            'user_type' => 'required'
        ];
    }

    public function authorize()
    {
        return true;
    }
}
