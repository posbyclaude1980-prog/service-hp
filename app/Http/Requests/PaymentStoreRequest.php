<?php

namespace App\Http\Requests;

use App\Enums\PaymentMethod;
use App\Http\Requests\Concerns\IndonesianValidationMessages;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PaymentStoreRequest extends FormRequest
{
    use IndonesianValidationMessages;

    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'service_order_id' => ['required', 'exists:service_orders,id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'method' => ['required', Rule::enum(PaymentMethod::class)],
            'payment_date' => ['required', 'date'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
