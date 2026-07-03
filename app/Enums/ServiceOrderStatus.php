<?php

namespace App\Enums;

enum ServiceOrderStatus: string
{
    case Received = 'received';
    case Diagnosis = 'diagnosis';
    case WaitingApproval = 'waiting_approval';
    case InService = 'in_service';
    case Qc = 'qc';
    case Completed = 'completed';
    case PickedUp = 'picked_up';
    case Warranty = 'warranty';
    case Closed = 'closed';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::Received => 'Diterima',
            self::Diagnosis => 'Diagnosa',
            self::WaitingApproval => 'Menunggu Persetujuan',
            self::InService => 'Proses Service',
            self::Qc => 'QC',
            self::Completed => 'Selesai',
            self::PickedUp => 'Sudah Diambil',
            self::Warranty => 'Garansi',
            self::Closed => 'Closed',
            self::Cancelled => 'Dibatalkan',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Received => 'secondary',
            self::Diagnosis => 'info',
            self::WaitingApproval => 'warning',
            self::InService => 'primary',
            self::Qc => 'info',
            self::Completed => 'success',
            self::PickedUp => 'dark',
            self::Warranty => 'warning',
            self::Closed => 'secondary',
            self::Cancelled => 'danger',
        };
    }

    public function order(): int
    {
        return match ($this) {
            self::Received => 1,
            self::Diagnosis => 2,
            self::WaitingApproval => 3,
            self::InService => 4,
            self::Qc => 5,
            self::Completed => 6,
            self::PickedUp => 7,
            self::Warranty => 8,
            self::Closed => 9,
            self::Cancelled => 99,
        };
    }

    /**
     * @return array<int, self>
     */
    public static function workflow(): array
    {
        return [
            self::Received,
            self::Diagnosis,
            self::WaitingApproval,
            self::InService,
            self::Qc,
            self::Completed,
            self::PickedUp,
            self::Warranty,
            self::Closed,
        ];
    }

    public function next(): ?self
    {
        $workflow = self::workflow();
        $index = array_search($this, $workflow, true);

        if ($index === false || ! isset($workflow[$index + 1])) {
            return null;
        }

        return $workflow[$index + 1];
    }

    public function isTerminal(): bool
    {
        return in_array($this, [self::Closed, self::Cancelled], true);
    }

    public function canTransitionTo(self $target, string $userRole): bool
    {
        if ($this === $target) {
            return false;
        }

        if ($target === self::Cancelled) {
            return in_array($userRole, ['owner', 'admin'], true);
        }

        if ($this === self::Cancelled || $this === self::Closed) {
            return false;
        }

        if (in_array($userRole, ['owner', 'admin'], true)) {
            return $target->order() > $this->order();
        }

        $allowed = match ($userRole) {
            'kasir' => [
                self::Received->value => [self::Diagnosis],
                self::WaitingApproval->value => [self::InService],
                self::Completed->value => [self::PickedUp],
                self::PickedUp->value => [self::Warranty],
            ],
            'teknisi' => [
                self::Received->value => [self::Diagnosis],
                self::Diagnosis->value => [self::WaitingApproval],
                self::WaitingApproval->value => [self::InService],
                self::InService->value => [self::Qc],
                self::Qc->value => [self::Completed, self::InService],
                self::Warranty->value => [self::Closed],
            ],
            default => [],
        };

        $from = $this->value;
        $targets = $allowed[$from] ?? [];

        if ($target === $this->next()) {
            return true;
        }

        foreach ($targets as $status) {
            if ($status === $target) {
                return true;
            }
        }

        return false;
    }
}
