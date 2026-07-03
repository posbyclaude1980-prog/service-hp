<?php

namespace App\Services;

use App\Models\ServiceInvoice;
use Illuminate\Support\Facades\DB;

class InvoiceNumberService
{
    public function generate(): string
    {
        $date = now()->format('Ymd');
        $prefix = "INV-{$date}-";

        return DB::transaction(function () use ($prefix): string {
            $last = ServiceInvoice::query()
                ->where('invoice_number', 'like', $prefix.'%')
                ->lockForUpdate()
                ->orderByDesc('invoice_number')
                ->value('invoice_number');

            $next = 1;

            if ($last !== null && preg_match('/-(\d+)$/', $last, $matches)) {
                $next = (int) $matches[1] + 1;
            }

            return $prefix.str_pad((string) $next, 6, '0', STR_PAD_LEFT);
        });
    }
}
