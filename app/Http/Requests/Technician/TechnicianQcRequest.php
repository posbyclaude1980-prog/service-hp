<?php

namespace App\Http\Requests\Technician;

use App\Http\Requests\Concerns\IndonesianValidationMessages;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TechnicianQcRequest extends FormRequest
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
            'qc_result' => ['required', Rule::in(['pass', 'fail'])],
            'notes' => ['required', 'string', 'max:2000'],
        ];
    }
}
