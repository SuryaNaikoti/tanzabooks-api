<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MoveFolderRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'tanzabook_id' => 'required|exists:tanzabooks,id',
            'folder_id' => 'required|exists:folders,id'
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
