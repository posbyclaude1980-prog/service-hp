<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\IndonesianValidationMessages;
use Illuminate\Foundation\Http\FormRequest;

class ServiceOrderUpdateRequest extends FormRequest
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
            'customer_id' => ['required', 'exists:customers,id'],
            'contact_phone' => ['required', 'string', 'max:20'],
            'device_brand' => ['required', 'string', 'max:255'],
            'device_model' => ['required', 'string', 'max:255'],
            'imei' => ['nullable', 'string', 'max:50'],
            'color' => ['nullable', 'string', 'max:50'],
            'device_password' => ['nullable', 'string', 'max:255'],
            'complaint' => ['required', 'string'],
            'completeness' => ['nullable', 'string'],
            'diagnosis' => ['nullable', 'string'],
            'estimated_cost' => ['nullable', 'numeric', 'min:0'],
            'service_fee' => ['nullable', 'numeric', 'min:0'],
            'technician_id' => ['nullable', 'exists:technicians,id'],
            'entry_date' => ['required', 'date'],
            'warranty_days' => ['nullable', 'integer', 'min:0'],
            'notes' => ['nullable', 'string'],
            'photo_front' => ['nullable', 'image', 'max:5120'],
            'photo_back' => ['nullable', 'image', 'max:5120'],
            'photo_lcd' => ['nullable', 'image', 'max:5120'],
            'photo_imei' => ['nullable', 'image', 'max:5120'],
            'items' => ['nullable', 'array'],
            'items.*.spare_part_id' => ['required_with:items', 'exists:spareparts,id'],
            'items.*.quantity' => ['required_with:items', 'integer', 'min:1'],
            'items.*.price' => ['nullable', 'numeric', 'min:0'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'customer_id' => 'pelanggan',
            'contact_phone' => 'nomor HP',
            'device_brand' => 'merk',
            'device_model' => 'model',
            'device_password' => 'password HP',
            'complaint' => 'keluhan',
            'completeness' => 'kelengkapan',
            'estimated_cost' => 'estimasi',
            'technician_id' => 'teknisi',
            'entry_date' => 'tanggal',
            'photo_front' => 'foto depan',
            'photo_back' => 'foto belakang',
            'photo_lcd' => 'foto LCD',
            'photo_imei' => 'foto IMEI',
        ];
    }
}
