<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTanzabookRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required',
            'file' => 'required|file',
            'type' => 'required|in:folder,group'
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
