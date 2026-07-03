<?php

namespace App\Services;

use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\SparePart;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class PurchaseService
{
    private const STOCK_REFERENCE = 'purchase';

    public function __construct(
        private readonly StockService $stockService,
        private readonly CodeGeneratorService $codeGeneratorService,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     * @param  array<int, array<string, mixed>>  $items
     */
    public function create(array $data, array $items, int $userId): Purchase
    {
        if ($items === []) {
            throw new RuntimeException('Purchase must contain at least one item.');
        }

        return DB::transaction(function () use ($data, $items, $userId): Purchase {
            $data['invoice_number'] = $data['invoice_number']
                ?? $this->codeGeneratorService->generateCode('PUR', Purchase::class, 'invoice_number');

            $data['user_id'] = $userId;
            $data['total'] = 0;

            $purchase = Purchase::query()->create($data);

            $total = 0;

            foreach ($items as $itemData) {
                $sparePartId = (int) $itemData['spare_part_id'];
                $quantity = (int) $itemData['quantity'];
                $price = (float) $itemData['price'];
                $subtotal = round($quantity * $price, 2);

                PurchaseItem::query()->create([
                    'purchase_id' => $purchase->id,
                    'spare_part_id' => $sparePartId,
                    'quantity' => $quantity,
                    'price' => $price,
                    'subtotal' => $subtotal,
                ]);

                $this->stockService->stockIn(
                    $sparePartId,
                    $quantity,
                    self::STOCK_REFERENCE,
                    $purchase->id,
                    "Purchase {$purchase->invoice_number}",
                    $userId,
                );

                SparePart::query()
                    ->where('id', $sparePartId)
                    ->update(['purchase_price' => $price]);

                $total += $subtotal;
            }

            $purchase->update(['total' => $total]);

            return $purchase->fresh(['items.sparePart', 'supplier', 'user']);
        });
    }
}
