<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAnnotationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'tanzabook_id' => 'required|exists:tanzabooks,id',
            'annotation_json' => 'required'
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
