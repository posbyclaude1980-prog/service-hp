<?php

namespace App\Services;

use App\Enums\PaymentMethod;
use App\Models\Payment;
use App\Models\PaymentMethodModel;
use App\Models\ServiceOrder;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class PaymentService
{
    public function __construct(
        private readonly InvoiceService $invoiceService,
    ) {}

    public function usesLegacySchema(): bool
    {
        return Schema::hasTable('payments')
            && ! Schema::hasColumn('payments', 'payment_method_id');
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array{payment: Payment, invoice: \App\Models\ServiceInvoice|null}
     */
    public function record(array $data, User $user): array
    {
        return DB::transaction(function () use ($data, $user): array {
            $payment = $this->usesLegacySchema()
                ? $this->createLegacyPayment($data, $user)
                : $this->createModernPayment($data, $user);

            $order = ServiceOrder::query()->lockForUpdate()->findOrFail($data['service_order_id']);
            $paidAmount = (float) Payment::query()
                ->where('service_order_id', $order->id)
                ->sum('amount');

            $order->update(['paid_amount' => $paidAmount]);

            $invoice = $this->invoiceService->generateIfComplete($order->fresh(), $user);

            return ['payment' => $payment, 'invoice' => $invoice];
        });
    }

    public function recalculatePaidAmount(int $serviceOrderId): void
    {
        DB::transaction(function () use ($serviceOrderId): void {
            $order = ServiceOrder::query()->lockForUpdate()->findOrFail($serviceOrderId);
            $paidAmount = (float) Payment::query()
                ->where('service_order_id', $order->id)
                ->sum('amount');

            $order->update(['paid_amount' => $paidAmount]);
        });
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function createLegacyPayment(array $data, User $user): Payment
    {
        return Payment::query()->create([
            'service_order_id' => $data['service_order_id'],
            'amount' => $data['amount'],
            'method' => $data['method'],
            'payment_date' => $data['payment_date'],
            'notes' => $data['notes'] ?? null,
            'user_id' => $user->id,
        ]);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function createModernPayment(array $data, User $user): Payment
    {
        $order = ServiceOrder::query()->findOrFail($data['service_order_id']);
        $method = $data['method'] instanceof PaymentMethod
            ? $data['method']
            : PaymentMethod::from($data['method']);

        $paymentMethodId = $this->resolvePaymentMethodId($method);

        return Payment::query()->create([
            'uuid' => (string) Str::uuid(),
            'service_order_id' => $order->id,
            'payment_method_id' => $paymentMethodId,
            'branch_id' => $order->branch_id ?? $user->branch_id,
            'received_by' => $user->id,
            'amount' => $data['amount'],
            'payment_date' => $data['payment_date'],
            'notes' => $data['notes'] ?? null,
        ]);
    }

    private function resolvePaymentMethodId(PaymentMethod $method): int
    {
        $model = PaymentMethodModel::query()->firstOrCreate(
            ['code' => $method->value],
            ['name' => $method->label(), 'is_active' => true]
        );

        return $model->id;
    }
}
