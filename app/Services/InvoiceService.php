<?php

namespace App\Services;

use App\Models\ServiceInvoice;
use App\Models\ServiceOrder;
use App\Models\User;
use Illuminate\Support\Facades\URL;

class InvoiceService
{
    public function __construct(
        private readonly InvoicePdfService $pdfService,
    ) {}

    public function isFullyPaid(ServiceOrder $order): bool
    {
        return (float) $order->total_cost > 0
            && (float) $order->paid_amount >= (float) $order->total_cost;
    }

    public function generateIfComplete(ServiceOrder $order, User $user): ?ServiceInvoice
    {
        $order->refresh();

        if (! $this->isFullyPaid($order)) {
            return null;
        }

        $result = $this->pdfService->generate($order, $user);

        return $result['invoice'];
    }

    public function whatsAppUrl(ServiceInvoice $invoice): string
    {
        $invoice->loadMissing('serviceOrder.customer');
        $order = $invoice->serviceOrder;
        $customer = $order->customer;

        $phone = $this->normalizeWhatsAppPhone(
            $order->contact_phone ?: $customer?->phone
        );

        $downloadUrl = URL::signedRoute('invoices.download', ['invoice' => $invoice->uuid]);
        $trackUrl = route('invoices.track', $order->uuid);
        $shop = app(SettingService::class)->get('shop_name', 'Service HP');

        $lines = [
            "Halo {$customer?->name},",
            '',
            "Terima kasih! Pembayaran service Anda di *{$shop}* telah lunas.",
            '',
            "No. Invoice : {$invoice->invoice_number}",
            "No. Service : {$order->ticket_number}",
            'Perangkat  : '.$order->device_brand.' '.$order->device_model,
            'Total      : Rp '.number_format((float) $invoice->total, 0, ',', '.'),
            'Garansi    : '.$order->warranty_days.' hari',
            '',
            "Unduh Invoice PDF:\n{$downloadUrl}",
            '',
            "Lacak status service:\n{$trackUrl}",
            '',
            'Terima kasih atas kepercayaan Anda.',
        ];

        $text = implode("\n", $lines);

        if ($phone === null) {
            return 'https://wa.me/?text='.rawurlencode($text);
        }

        return 'https://wa.me/'.$phone.'?text='.rawurlencode($text);
    }

    private function normalizeWhatsAppPhone(?string $phone): ?string
    {
        if ($phone === null || trim($phone) === '') {
            return null;
        }

        $digits = preg_replace('/\D/', '', $phone);

        if ($digits === '') {
            return null;
        }

        if (str_starts_with($digits, '0')) {
            return '62'.substr($digits, 1);
        }

        if (str_starts_with($digits, '62')) {
            return $digits;
        }

        return $digits;
    }
}
