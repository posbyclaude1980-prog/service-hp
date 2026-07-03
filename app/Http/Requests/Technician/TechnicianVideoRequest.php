<?php

namespace App\Http\Requests\Technician;

use App\Http\Requests\Concerns\IndonesianValidationMessages;
use Illuminate\Foundation\Http\FormRequest;

class TechnicianVideoRequest extends FormRequest
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
            'video' => ['required', 'file', 'mimetypes:video/mp4,video/quicktime,video/x-msvideo,video/webm', 'max:51200'],
            'caption' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
