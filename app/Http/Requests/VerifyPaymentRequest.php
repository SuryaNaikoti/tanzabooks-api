<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VerifyPaymentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'razorpay_order_id' => 'required|exists:payments,order_id'
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
