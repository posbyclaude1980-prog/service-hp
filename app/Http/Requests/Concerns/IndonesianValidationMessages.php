<?php

namespace App\Http\Requests\Concerns;

trait IndonesianValidationMessages
{
    /**
     * @return array<string, string>
     */
    protected function indonesianMessages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'string' => ':attribute harus berupa teks.',
            'email' => ':attribute harus berupa alamat email yang valid.',
            'unique' => ':attribute sudah digunakan.',
            'exists' => ':attribute tidak ditemukan.',
            'numeric' => ':attribute harus berupa angka.',
            'integer' => ':attribute harus berupa bilangan bulat.',
            'min' => ':attribute minimal :min.',
            'max' => ':attribute maksimal :max karakter.',
            'date' => ':attribute harus berupa tanggal yang valid.',
            'confirmed' => 'Konfirmasi :attribute tidak cocok.',
            'in' => ':attribute tidak valid.',
            'array' => ':attribute harus berupa daftar.',
            'image' => ':attribute harus berupa gambar.',
            'mimes' => ':attribute harus berformat: :values.',
            'max.file' => ':attribute maksimal :max kilobyte.',
            'boolean' => ':attribute harus berupa ya atau tidak.',
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function indonesianAttributes(): array
    {
        return [
            'name' => 'nama',
            'email' => 'email',
            'password' => 'kata sandi',
            'phone' => 'telepon',
            'address' => 'alamat',
            'code' => 'kode',
            'brand_id' => 'merek',
            'supplier_id' => 'supplier',
            'customer_id' => 'pelanggan',
            'phone_model_id' => 'model HP',
            'technician_id' => 'teknisi',
            'service_order_id' => 'order servis',
            'complaint' => 'keluhan',
            'diagnosis' => 'diagnosis',
            'service_fee' => 'biaya servis',
            'entry_date' => 'tanggal masuk',
            'completion_date' => 'tanggal selesai',
            'pickup_date' => 'tanggal pengambilan',
            'warranty_days' => 'garansi (hari)',
            'notes' => 'catatan',
            'status' => 'status',
            'amount' => 'jumlah',
            'method' => 'metode pembayaran',
            'payment_date' => 'tanggal pembayaran',
            'purchase_date' => 'tanggal pembelian',
            'stock' => 'stok',
            'min_stock' => 'stok minimum',
            'purchase_price' => 'harga beli',
            'selling_price' => 'harga jual',
            'description' => 'deskripsi',
            'contact' => 'kontak',
            'role' => 'peran',
            'is_active' => 'status aktif',
            'device_brand' => 'merek perangkat',
            'device_model' => 'model perangkat',
            'imei' => 'IMEI',
            'items' => 'item',
            'items.*.spare_part_id' => 'sparepart',
            'items.*.quantity' => 'jumlah',
            'items.*.price' => 'harga',
            'logo' => 'logo',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return $this->indonesianMessages();
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return $this->indonesianAttributes();
    }
}
