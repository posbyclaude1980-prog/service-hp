<?php

require __DIR__.'/../vendor/autoload.php';
$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$order = \App\Models\ServiceOrder::query()->first();
$user = \App\Models\User::query()->first();

if (! $order || ! $user) {
    echo "No data\n";
    exit(1);
}

$order->update(['paid_amount' => $order->total_cost ?: 500000, 'total_cost' => $order->total_cost ?: 500000]);

$result = app(\App\Services\InvoicePdfService::class)->generate($order->fresh(), $user);

echo "OK: {$result['invoice']->invoice_number} -> {$result['path']}\n";
