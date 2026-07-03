<?php

namespace App\Services;

use App\Models\ServiceInvoice;
use App\Models\ServiceOrder;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;

class InvoicePdfService
{
    public function __construct(
        private readonly SettingService $settingService,
        private readonly InvoiceCodeService $codeService,
        private readonly InvoiceNumberService $numberService,
    ) {}

    /**
     * @return array{invoice: ServiceInvoice, path: string}
     */
    public function generate(ServiceOrder $order, User $user): array
    {
        $order->loadMissing(['customer', 'technician.user', 'items.sparePart', 'invoice']);

        $settings = $this->settingService->getAll();
        $invoiceNumber = $order->invoice?->invoice_number ?? $this->numberService->generate();
        $subtotal = (float) $order->service_fee + (float) $order->parts_total;

        $invoice = DB::transaction(function () use ($order, $user, $invoiceNumber, $subtotal): ServiceInvoice {
            if ($order->invoice) {
                $order->invoice->update([
                    'invoice_number' => $invoiceNumber,
                    'subtotal' => $subtotal,
                    'discount' => (float) $order->discount,
                    'tax' => (float) $order->tax,
                    'total' => (float) $order->total_cost,
                    'paid_amount' => (float) $order->paid_amount,
                    'generated_by' => $user->id,
                    'generated_at' => now(),
                ]);

                return $order->invoice->fresh();
            }

            return ServiceInvoice::query()->create([
                'uuid' => (string) Str::uuid(),
                'service_order_id' => $order->id,
                'invoice_number' => $invoiceNumber,
                'pdf_path' => 'invoices/'.$order->id.'/pending.pdf',
                'subtotal' => $subtotal,
                'discount' => (float) $order->discount,
                'tax' => (float) $order->tax,
                'total' => (float) $order->total_cost,
                'paid_amount' => (float) $order->paid_amount,
                'generated_by' => $user->id,
                'generated_at' => now(),
            ]);
        });

        $filename = 'invoice-'.$invoiceNumber.'.pdf';
        $path = "invoices/{$order->id}/{$filename}";

        $pdfBinary = $this->renderPdf($order, $settings, $invoice);
        Storage::disk('public')->put($path, $pdfBinary);

        if ($invoice->pdf_path !== $path && $invoice->pdf_path !== 'invoices/'.$order->id.'/pending.pdf') {
            Storage::disk('public')->delete($invoice->pdf_path);
        }

        $invoice->update(['pdf_path' => $path]);

        return ['invoice' => $invoice->fresh(), 'path' => $path];
    }

  /**
     * @param  \Illuminate\Support\Collection<string, mixed>  $settings
     */
    private function renderPdf(ServiceOrder $order, $settings, ServiceInvoice $invoice): string
    {
        $trackingUrl = route('invoices.track', $order->uuid);
        $invoiceUrl = URL::signedRoute('invoices.download', ['invoice' => $invoice->uuid]);

        $subtotal = (float) $order->service_fee + (float) $order->parts_total;

        $viewData = [
            'serviceOrder' => $order,
            'invoice' => $invoice,
            'settings' => $settings,
            'invoiceNumber' => $invoice->invoice_number,
            'logoBase64' => $this->resolveLogoBase64($settings->get('shop_logo')),
            'qrTracking' => $this->codeService->qrCodeBase64($trackingUrl),
            'qrInvoice' => $this->codeService->qrCodeBase64($invoiceUrl),
            'barcode' => $this->codeService->barcodeBase64($invoice->invoice_number),
            'trackingUrl' => $trackingUrl,
            'subtotal' => $subtotal,
            'discount' => (float) $order->discount,
            'tax' => (float) $order->tax,
            'total' => (float) $order->total_cost,
            'paidAmount' => (float) $order->paid_amount,
            'generatedAt' => $invoice->generated_at ?? now(),
        ];

        return Pdf::loadView('invoices.pdf', $viewData)
            ->setPaper('a4', 'portrait')
            ->output();
    }

    private function resolveLogoBase64(?string $logoPath): ?string
    {
        if ($logoPath === null || $logoPath === '') {
            return null;
        }

        if (! Storage::disk('public')->exists($logoPath)) {
            return null;
        }

        $mime = match (strtolower(pathinfo($logoPath, PATHINFO_EXTENSION))) {
            'png' => 'image/png',
            'jpg', 'jpeg' => 'image/jpeg',
            'webp' => 'image/webp',
            'gif' => 'image/gif',
            default => 'image/png',
        };

        return 'data:'.$mime.';base64,'.base64_encode(Storage::disk('public')->get($logoPath));
    }
}
