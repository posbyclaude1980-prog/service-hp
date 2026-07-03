<?php

namespace App\Services;

use App\Models\ServiceOrder;
use Illuminate\Support\Facades\DB;

class TicketNumberService
{
    public function generate(): string
    {
        $date = now()->format('Ymd');
        $prefix = "SRV-{$date}-";

        return DB::transaction(function () use ($prefix): string {
            $lastTicket = ServiceOrder::query()
                ->where('ticket_number', 'like', $prefix.'%')
                ->lockForUpdate()
                ->orderByDesc('ticket_number')
                ->value('ticket_number');

            $nextSequence = 1;

            if ($lastTicket !== null && preg_match('/-(\d+)$/', $lastTicket, $matches)) {
                $nextSequence = (int) $matches[1] + 1;
            }

            return $prefix.str_pad((string) $nextSequence, 6, '0', STR_PAD_LEFT);
        });
    }

    public function preview(): string
    {
        $date = now()->format('Ymd');
        $prefix = "SRV-{$date}-";

        $lastTicket = ServiceOrder::query()
            ->where('ticket_number', 'like', $prefix.'%')
            ->orderByDesc('ticket_number')
            ->value('ticket_number');

        $nextSequence = 1;

        if ($lastTicket !== null && preg_match('/-(\d+)$/', $lastTicket, $matches)) {
            $nextSequence = (int) $matches[1] + 1;
        }

        return $prefix.str_pad((string) $nextSequence, 6, '0', STR_PAD_LEFT);
    }
}
