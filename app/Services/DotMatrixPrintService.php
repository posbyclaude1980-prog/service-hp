<?php

namespace App\Services;

use App\Enums\PrintPaperSize;
use App\Models\Payment;
use App\Models\ServiceOrder;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class DotMatrixPrintService
{
    public function __construct(
        private readonly SettingService $settingService,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function context(Request $request): array
    {
        $paper = PrintPaperSize::fromRequest($request->query('paper'));
        $preview = $request->boolean('preview', ! $request->boolean('print'));
        $autoPrint = $request->boolean('print') && ! $preview;

        return [
            'paper' => $paper,
            'paperClass' => $paper->cssClass(),
            'columns' => $paper->columns(),
            'preview' => $preview,
            'autoPrint' => $autoPrint,
            'settings' => $this->settingService->getAll(),
            'printedAt' => now(),
        ];
    }

    public function line(int $columns, string $char = '='): string
    {
        return str_repeat($char, max(10, $columns));
    }

    public function padRight(string $text, int $width): string
    {
        $text = mb_substr($text, 0, $width);

        return str_pad($text, $width);
    }

    public function money(float|int|string $amount): string
    {
        return 'Rp '.number_format((float) $amount, 0, ',', '.');
    }

    /**
     * @return array<string, mixed>
     */
    public function dailyReportData(Carbon $date): array
    {
        $orders = ServiceOrder::query()
            ->with(['customer', 'technician.user'])
            ->whereDate('entry_date', $date)
            ->orderBy('ticket_number')
            ->get();

        $payments = Payment::query()
            ->with(['serviceOrder', 'user'])
            ->whereDate('payment_date', $date)
            ->orderBy('payment_date')
            ->get();

        return [
            'date' => $date,
            'orders' => $orders,
            'payments' => $payments,
            'summary' => [
                'order_count' => $orders->count(),
                'order_value' => (float) $orders->sum('total_cost'),
                'payment_count' => $payments->count(),
                'payment_total' => (float) $payments->sum('amount'),
                'completed' => $orders->filter(
                    fn ($o) => in_array($o->status?->value, ['completed', 'picked_up', 'closed'], true)
                )->count(),
                'in_progress' => $orders->filter(
                    fn ($o) => ! in_array($o->status?->value, ['completed', 'picked_up', 'closed', 'cancelled'], true)
                )->count(),
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function monthlyReportData(int $year, int $month): array
    {
        $start = Carbon::create($year, $month, 1)->startOfMonth();
        $end = $start->copy()->endOfMonth();

        $orders = ServiceOrder::query()
            ->with(['customer', 'technician.user'])
            ->whereBetween('entry_date', [$start->toDateString(), $end->toDateString()])
            ->orderBy('entry_date')
            ->orderBy('ticket_number')
            ->get();

        $payments = Payment::query()
            ->with(['serviceOrder', 'user'])
            ->whereBetween('payment_date', [$start->toDateString(), $end->toDateString()])
            ->orderBy('payment_date')
            ->get();

        $dailyPayments = $payments
            ->groupBy(fn (Payment $p) => $p->payment_date?->format('Y-m-d') ?? '')
            ->map(fn (Collection $group) => (float) $group->sum('amount'));

        return [
            'period' => $start,
            'start' => $start,
            'end' => $end,
            'orders' => $orders,
            'payments' => $payments,
            'dailyPayments' => $dailyPayments,
            'summary' => [
                'order_count' => $orders->count(),
                'order_value' => (float) $orders->sum('total_cost'),
                'payment_count' => $payments->count(),
                'payment_total' => (float) $payments->sum('amount'),
                'completed' => $orders->filter(
                    fn ($o) => in_array($o->status?->value, ['completed', 'picked_up', 'closed'], true)
                )->count(),
                'cancelled' => $orders->filter(
                    fn ($o) => $o->status?->value === 'cancelled'
                )->count(),
            ],
        ];
    }
}
