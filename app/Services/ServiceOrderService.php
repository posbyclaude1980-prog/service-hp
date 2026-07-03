<?php

namespace App\Services;

use App\Enums\ServiceOrderStatus;
use App\Models\Branch;
use App\Models\ServiceOrder;
use App\Models\ServiceOrderItem;
use App\Models\User;
use App\Repositories\Contracts\ServiceOrderRepositoryInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;

class ServiceOrderService
{
    private const STOCK_REFERENCE = 'service_order';

    public function __construct(
        private readonly ServiceOrderRepositoryInterface $serviceOrderRepository,
        private readonly TicketNumberService $ticketNumberService,
        private readonly StockService $stockService,
        private readonly ServiceOrderStatusHistoryService $statusHistoryService,
        private readonly ServiceOrderPhotoService $photoService,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     * @param  array<int, array<string, mixed>>  $items
     * @param  array<string, UploadedFile|null>  $photos
     */
    public function create(
        array $data,
        array $items = [],
        ?int $userId = null,
        array $photos = [],
    ): ServiceOrder {
        return DB::transaction(function () use ($data, $items, $userId, $photos): ServiceOrder {
            $data['uuid'] = $data['uuid'] ?? (string) Str::uuid();
            $data['ticket_number'] = $data['ticket_number'] ?? $this->ticketNumberService->generate();
            $data['branch_id'] = $data['branch_id'] ?? $this->resolveBranchId($userId);
            $data['created_by'] = $userId;
            $data['status'] = $data['status'] ?? ServiceOrderStatus::Received->value;
            $data['service_fee'] = $data['service_fee'] ?? 0;
            $data['parts_total'] = $data['parts_total'] ?? 0;
            $data['total_cost'] = $data['total_cost'] ?? ($data['estimated_cost'] ?? 0);

            $order = ServiceOrder::query()->create($data);

            if ($userId !== null) {
                $this->statusHistoryService->record(
                    order: $order,
                    toStatus: ServiceOrderStatus::from($data['status']),
                    userId: $userId,
                    notes: 'Penerimaan service HP',
                    photo: $photos['photo_status'] ?? null,
                    fromStatus: null,
                );
            }

            if ($photos !== []) {
                $this->photoService->storeIntakePhotos($order, $photos, $userId);
            }

            if ($items !== []) {
                $this->syncItems($order, $items, $userId);
            } else {
                $this->recalculateTotals($order);
            }

            return $order->fresh([
                'items.sparePart',
                'customer',
                'technician.user',
                'statusHistories.user',
                'intakeImages',
            ]);
        });
    }

    /**
     * @param  array<string, mixed>  $data
     * @param  array<int, array<string, mixed>>|null  $items
     * @param  array<string, UploadedFile|null>  $photos
     */
    public function update(
        int $id,
        array $data,
        ?array $items = null,
        ?int $userId = null,
        array $photos = [],
    ): ServiceOrder {
        return DB::transaction(function () use ($id, $data, $items, $userId, $photos): ServiceOrder {
            $order = $this->serviceOrderRepository->findWithRelations($id, ['items']);

            if ($order === null) {
                throw new RuntimeException("Service order [{$id}] not found.");
            }

            if ($order->status === ServiceOrderStatus::Cancelled) {
                throw new RuntimeException('Cannot update a cancelled service order.');
            }

            unset($data['ticket_number'], $data['status'], $data['uuid'], $data['branch_id'], $data['created_by']);

            $order->update($data);

            if ($photos !== []) {
                $this->photoService->storeIntakePhotos($order, $photos, $userId);
            }

            if ($items !== null) {
                $this->syncItems($order, $items, $userId);
            } else {
                $this->recalculateTotals($order);
            }

            return $order->fresh([
                'items.sparePart',
                'customer',
                'technician.user',
                'statusHistories.user',
                'intakeImages',
            ]);
        });
    }

    public function updateStatus(
        int $id,
        string $status,
        User $user,
        ?string $notes = null,
        ?UploadedFile $photo = null,
    ): ServiceOrder {
        return DB::transaction(function () use ($id, $status, $user, $notes, $photo): ServiceOrder {
            $order = $this->serviceOrderRepository->findWithRelations($id, ['items']);

            if ($order === null) {
                throw new RuntimeException("Service order [{$id}] not found.");
            }

            $target = ServiceOrderStatus::from($status);

            if ($target === ServiceOrderStatus::Cancelled) {
                $this->restoreStockForOrder($order, $user->id);
            }

            return $this->statusHistoryService->transition($order, $target, $user, $notes, $photo);
        });
    }

    public function recalculateTotals(ServiceOrder $order): ServiceOrder
    {
        $order->loadMissing('items');

        $partsTotal = $order->items->sum('subtotal');
        $totalCost = (float) $order->service_fee + (float) $partsTotal - (float) $order->discount + (float) $order->tax;

        $order->update([
            'parts_total' => $partsTotal,
            'total_cost' => max(0, $totalCost),
        ]);

        return $order->fresh();
    }

    /**
     * @param  array<int, array<string, mixed>>  $items
     */
    public function syncItems(ServiceOrder $order, array $items, ?int $userId = null): ServiceOrder
    {
        return DB::transaction(function () use ($order, $items, $userId): ServiceOrder {
            if ($order->status === ServiceOrderStatus::Cancelled) {
                throw new RuntimeException('Cannot modify items on a cancelled service order.');
            }

            $order->loadMissing('items');
            $existingItems = $order->items->keyBy('sparepart_id');

            $incomingSparePartIds = [];

            foreach ($items as $itemData) {
                if (empty($itemData['spare_part_id'])) {
                    continue;
                }

                $sparePartId = (int) $itemData['spare_part_id'];
                $quantity = (int) $itemData['quantity'];
                $price = (float) ($itemData['price'] ?? 0);
                $subtotal = round($quantity * $price, 2);

                $incomingSparePartIds[] = $sparePartId;

                if ($existingItems->has($sparePartId)) {
                    $existingItem = $existingItems->get($sparePartId);
                    $quantityDiff = $quantity - $existingItem->quantity;

                    if ($quantityDiff > 0 && $userId !== null) {
                        $this->stockService->stockOut(
                            $sparePartId,
                            $quantityDiff,
                            self::STOCK_REFERENCE,
                            $order->id,
                            "Service order {$order->ticket_number}",
                            $userId,
                        );
                    } elseif ($quantityDiff < 0 && $userId !== null) {
                        $this->stockService->stockIn(
                            $sparePartId,
                            abs($quantityDiff),
                            self::STOCK_REFERENCE,
                            $order->id,
                            "Service order {$order->ticket_number} adjustment",
                            $userId,
                        );
                    }

                    $existingItem->update([
                        'quantity' => $quantity,
                        'unit_price' => $price,
                        'subtotal' => $subtotal,
                    ]);
                } else {
                    if ($userId !== null) {
                        $this->stockService->stockOut(
                            $sparePartId,
                            $quantity,
                            self::STOCK_REFERENCE,
                            $order->id,
                            "Service order {$order->ticket_number}",
                            $userId,
                        );
                    }

                    ServiceOrderItem::query()->create([
                        'service_order_id' => $order->id,
                        'sparepart_id' => $sparePartId,
                        'quantity' => $quantity,
                        'unit_price' => $price,
                        'subtotal' => $subtotal,
                    ]);
                }
            }

            foreach ($existingItems as $sparePartId => $existingItem) {
                if (! in_array($sparePartId, $incomingSparePartIds, true)) {
                    if ($userId !== null) {
                        $this->stockService->stockIn(
                            (int) $sparePartId,
                            $existingItem->quantity,
                            self::STOCK_REFERENCE,
                            $order->id,
                            "Service order {$order->ticket_number} item removed",
                            $userId,
                        );
                    }

                    $existingItem->delete();
                }
            }

            return $this->recalculateTotals($order->fresh(['items']));
        });
    }

    private function resolveBranchId(?int $userId): int
    {
        if ($userId !== null) {
            $branchId = User::query()->whereKey($userId)->value('branch_id');

            if ($branchId !== null) {
                return (int) $branchId;
            }
        }

        $branchId = Branch::query()->value('id');

        if ($branchId === null) {
            throw new RuntimeException('Tidak ada cabang aktif. Tambahkan cabang terlebih dahulu.');
        }

        return (int) $branchId;
    }

    private function restoreStockForOrder(ServiceOrder $order, ?int $userId): void
    {
        if ($userId === null) {
            return;
        }

        foreach ($order->items as $item) {
            $this->stockService->stockIn(
                $item->sparepart_id,
                $item->quantity,
                self::STOCK_REFERENCE,
                $order->id,
                "Service order {$order->ticket_number} cancelled",
                $userId,
            );
        }
    }
}
