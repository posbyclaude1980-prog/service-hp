<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class ServiceInvoice extends Model
{
    protected $fillable = [
        'uuid',
        'service_order_id',
        'invoice_number',
        'pdf_path',
        'subtotal',
        'discount',
        'tax',
        'total',
        'paid_amount',
        'generated_by',
        'generated_at',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'discount' => 'decimal:2',
            'tax' => 'decimal:2',
            'total' => 'decimal:2',
            'paid_amount' => 'decimal:2',
            'generated_at' => 'datetime',
        ];
    }

    public function serviceOrder(): BelongsTo
    {
        return $this->belongsTo(ServiceOrder::class);
    }

    public function generator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'generated_by');
    }

    public function pdfExists(): bool
    {
        return Storage::disk('public')->exists($this->pdf_path);
    }

    public function pdfUrl(): string
    {
        return Storage::disk('public')->url($this->pdf_path);
    }
}
