<?php

namespace App\Services;

use App\Models\SparePart;
use App\Models\StockMutation;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class StockService
{
    public function stockIn(
        int $sparePartId,
        int $qty,
        ?string $referenceType,
        ?int $referenceId,
        ?string $notes,
        int $userId,
    ): StockMutation {
        if ($qty <= 0) {
            throw new RuntimeException('Stock in quantity must be greater than zero.');
        }

        return DB::transaction(function () use ($sparePartId, $qty, $referenceType, $referenceId, $notes, $userId): StockMutation {
            $sparePart = SparePart::query()->lockForUpdate()->findOrFail($sparePartId);

            $stockBefore = $sparePart->stock;
            $stockAfter = $stockBefore + $qty;

            $sparePart->update(['stock' => $stockAfter]);

            return StockMutation::query()->create([
                'spare_part_id' => $sparePartId,
                'type' => 'in',
                'quantity' => $qty,
                'stock_before' => $stockBefore,
                'stock_after' => $stockAfter,
                'reference_type' => $referenceType,
                'reference_id' => $referenceId,
                'notes' => $notes,
                'user_id' => $userId,
            ]);
        });
    }

    public function stockOut(
        int $sparePartId,
        int $qty,
        ?string $referenceType,
        ?int $referenceId,
        ?string $notes,
        int $userId,
    ): StockMutation {
        if ($qty <= 0) {
            throw new RuntimeException('Stock out quantity must be greater than zero.');
        }

        return DB::transaction(function () use ($sparePartId, $qty, $referenceType, $referenceId, $notes, $userId): StockMutation {
            $sparePart = SparePart::query()->lockForUpdate()->findOrFail($sparePartId);

            if ($sparePart->stock < $qty) {
                throw new RuntimeException(
                    "Insufficient stock for {$sparePart->name}. Available: {$sparePart->stock}, requested: {$qty}."
                );
            }

            $stockBefore = $sparePart->stock;
            $stockAfter = $stockBefore - $qty;

            $sparePart->update(['stock' => $stockAfter]);

            return StockMutation::query()->create([
                'spare_part_id' => $sparePartId,
                'type' => 'out',
                'quantity' => $qty,
                'stock_before' => $stockBefore,
                'stock_after' => $stockAfter,
                'reference_type' => $referenceType,
                'reference_id' => $referenceId,
                'notes' => $notes,
                'user_id' => $userId,
            ]);
        });
    }
}
