<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGroupUsersRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'group_id' => 'required|exists:groups,id'
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
