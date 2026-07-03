<?php

namespace App\Http\Requests\Technician;

use App\Enums\ServiceOrderStatus;
use App\Http\Requests\Concerns\IndonesianValidationMessages;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TechnicianDiagnosisRequest extends FormRequest
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
            'diagnosis' => ['required', 'string'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
