<?php

namespace App\Http\Requests\Technician;

use App\Enums\ServiceOrderStatus;
use App\Http\Requests\Concerns\IndonesianValidationMessages;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TechnicianStatusRequest extends FormRequest
{
    use IndonesianValidationMessages;

    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'status' => ['required', Rule::enum(ServiceOrderStatus::class)],
            'notes' => ['required', 'string', 'max:2000'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:5120'],
            'diagnosis' => ['nullable', 'string'],
        ];
    }
}
