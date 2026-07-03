<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\IndonesianValidationMessages;
use Illuminate\Foundation\Http\FormRequest;

class SettingUpdateRequest extends FormRequest
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
            'shop_name' => ['nullable', 'string', 'max:255'],
            'shop_address' => ['nullable', 'string'],
            'shop_phone' => ['nullable', 'string', 'max:20'],
            'shop_email' => ['nullable', 'email', 'max:255'],
            'tax_percentage' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'invoice_footer' => ['nullable', 'string'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ];
    }
}
