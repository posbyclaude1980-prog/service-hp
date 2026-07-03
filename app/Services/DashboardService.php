<?php

namespace App\Services;

use App\Enums\ServiceOrderStatus;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DashboardService
{
    private bool $useLegacySchema;

    public function __construct()
    {
        $this->useLegacySchema = Schema::hasTable('spare_parts') && ! Schema::hasColumn('payments', 'payment_method_id');
    }

    /**
     * @return array<string, mixed>
     */
    public function getDashboardData(): array
    {
        return $this->useLegacySchema
            ? $this->legacyDashboardData()
            : $this->modernDashboardData();
    }

    /**
     * @return array<string, mixed>
     */
    private function modernDashboardData(): array
    {
        $today = now()->toDateString();
        $startOfMonth = now()->startOfMonth()->toDateString();
        $endOfMonth = now()->endOfMonth()->toDateString();

        $pendingStatuses = ['received', 'diagnosis', 'waiting_approval', 'in_service', 'qc'];
        $completedStatuses = ['completed', 'picked_up', 'closed'];

        $stats = [
            'services_today' => DB::table('service_orders')
                ->whereDate('entry_date', $today)
                ->whereNull('deleted_at')
                ->count(),
            'services_pending' => DB::table('service_orders')
                ->whereIn('status', $pendingStatuses)
                ->whereNull('deleted_at')
                ->count(),
            'services_completed' => DB::table('service_orders')
                ->whereIn('status', $completedStatuses)
                ->whereNull('deleted_at')
                ->count(),
            'services_warranty' => DB::table('service_orders')
                ->where('status', 'warranty')
                ->whereNull('deleted_at')
                ->count(),
            'revenue_today' => (float) DB::table('payments')
                ->whereDate('payment_date', $today)
                ->whereNull('deleted_at')
                ->sum('amount'),
            'revenue_month' => (float) DB::table('payments')
                ->whereBetween('payment_date', [$startOfMonth, $endOfMonth])
                ->whereNull('deleted_at')
                ->sum('amount'),
        ];

        $cogsMonth = (float) DB::table('service_items')
            ->join('service_orders', 'service_orders.id', '=', 'service_items.service_order_id')
            ->join('spareparts', 'spareparts.id', '=', 'service_items.sparepart_id')
            ->whereBetween('service_orders.entry_date', [$startOfMonth, $endOfMonth])
            ->whereNull('service_orders.deleted_at')
            ->sum(DB::raw('service_items.quantity * spareparts.purchase_price'));

        $expensesMonth = Schema::hasTable('expenses')
            ? (float) DB::table('expenses')
                ->whereBetween('expense_date', [$startOfMonth, $endOfMonth])
                ->whereNull('deleted_at')
                ->sum('amount')
            : 0;

        $stats['profit'] = $stats['revenue_month'] - $cogsMonth - $expensesMonth;
        $stats['cogs_month'] = $cogsMonth;
        $stats['expenses_month'] = $expensesMonth;

        $topTechnician = DB::table('service_orders')
            ->join('technicians', 'technicians.id', '=', 'service_orders.technician_id')
            ->join('users', 'users.id', '=', 'technicians.user_id')
            ->whereIn('service_orders.status', array_merge($completedStatuses, ['warranty']))
            ->whereNull('service_orders.deleted_at')
            ->select('users.name', DB::raw('COUNT(*) as total'))
            ->groupBy('users.id', 'users.name')
            ->orderByDesc('total')
            ->first();

        $stats['top_technician_name'] = $topTechnician->name ?? '-';
        $stats['top_technician_count'] = (int) ($topTechnician->total ?? 0);

        $recentOrders = DB::table('service_orders')
            ->join('customers', 'customers.id', '=', 'service_orders.customer_id')
            ->whereNull('service_orders.deleted_at')
            ->select(
                'service_orders.id',
                'service_orders.ticket_number',
                'service_orders.status',
                'service_orders.total_cost',
                'service_orders.entry_date',
                'customers.name as customer_name',
            )
            ->orderByDesc('service_orders.created_at')
            ->limit(10)
            ->get()
            ->map(function ($row) {
                $row->status_label = ServiceOrderStatus::tryFrom($row->status)?->label() ?? $row->status;
                $row->status_color = ServiceOrderStatus::tryFrom($row->status)?->color() ?? 'secondary';

                return $row;
            });

        $months = collect(range(11, 0))->map(fn (int $i) => now()->subMonths($i)->format('Y-m'));

        $serviceByMonth = DB::table('service_orders')
            ->select(DB::raw('DATE_FORMAT(entry_date, "%Y-%m") as month'), DB::raw('COUNT(*) as total'))
            ->whereDate('entry_date', '>=', now()->subMonths(11)->startOfMonth())
            ->whereNull('deleted_at')
            ->groupBy('month')
            ->pluck('total', 'month');

        $revenueByMonth = DB::table('payments')
            ->select(DB::raw('DATE_FORMAT(payment_date, "%Y-%m") as month'), DB::raw('SUM(amount) as total'))
            ->whereDate('payment_date', '>=', now()->subMonths(11)->startOfMonth())
            ->whereNull('deleted_at')
            ->groupBy('month')
            ->pluck('total', 'month');

        $sparepartUsage = DB::table('service_items')
            ->join('spareparts', 'spareparts.id', '=', 'service_items.sparepart_id')
            ->join('service_orders', 'service_orders.id', '=', 'service_items.service_order_id')
            ->whereBetween('service_orders.entry_date', [$startOfMonth, $endOfMonth])
            ->whereNull('service_orders.deleted_at')
            ->select('spareparts.name', DB::raw('SUM(service_items.quantity) as qty'))
            ->groupBy('spareparts.id', 'spareparts.name')
            ->orderByDesc('qty')
            ->limit(8)
            ->get();

        return [
            'stats' => $stats,
            'recentOrders' => $recentOrders,
            'charts' => [
                'month_labels' => $months->map(fn (string $m) => Carbon::createFromFormat('Y-m', $m)->translatedFormat('M Y'))->values()->all(),
                'service_monthly' => $months->map(fn (string $m) => (int) ($serviceByMonth[$m] ?? 0))->values()->all(),
                'revenue_monthly' => $months->map(fn (string $m) => (float) ($revenueByMonth[$m] ?? 0))->values()->all(),
                'sparepart_labels' => $sparepartUsage->pluck('name')->all(),
                'sparepart_qty' => $sparepartUsage->pluck('qty')->map(fn ($q) => (int) $q)->all(),
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function legacyDashboardData(): array
    {
        $today = now()->toDateString();
        $startOfMonth = now()->startOfMonth()->toDateString();
        $endOfMonth = now()->endOfMonth()->toDateString();

        $pendingStatuses = ['received', 'diagnosis', 'waiting_approval', 'in_service', 'qc', 'waiting_parts', 'in_repair'];

        $stats = [
            'services_today' => DB::table('service_orders')->whereDate('entry_date', $today)->whereNull('deleted_at')->count(),
            'services_pending' => DB::table('service_orders')->whereIn('status', $pendingStatuses)->whereNull('deleted_at')->count(),
            'services_completed' => DB::table('service_orders')->whereIn('status', ['completed', 'picked_up', 'closed'])->whereNull('deleted_at')->count(),
            'services_warranty' => DB::table('service_orders')->where('status', 'warranty')->whereNull('deleted_at')->count(),
            'revenue_today' => (float) DB::table('payments')->whereDate('payment_date', $today)->sum('amount'),
            'revenue_month' => (float) DB::table('payments')->whereBetween('payment_date', [$startOfMonth, $endOfMonth])->sum('amount'),
        ];

        $itemsTable = Schema::hasTable('service_items') ? 'service_items' : 'service_order_items';
        $spareTable = Schema::hasTable('spareparts') ? 'spareparts' : 'spare_parts';
        $spareFk = Schema::hasTable('spareparts') ? 'sparepart_id' : 'spare_part_id';
        $priceCol = Schema::hasColumn($spareTable, 'purchase_price') ? 'purchase_price' : 'purchase_price';

        $cogsMonth = (float) DB::table($itemsTable)
            ->join('service_orders', 'service_orders.id', '=', "{$itemsTable}.service_order_id")
            ->join($spareTable, "{$spareTable}.id", '=', "{$itemsTable}.{$spareFk}")
            ->whereBetween('service_orders.entry_date', [$startOfMonth, $endOfMonth])
            ->whereNull('service_orders.deleted_at')
            ->sum(DB::raw("{$itemsTable}.quantity * {$spareTable}.{$priceCol}"));

        $stats['profit'] = $stats['revenue_month'] - $cogsMonth;
        $stats['cogs_month'] = $cogsMonth;
        $stats['expenses_month'] = 0;

        $topTechnician = DB::table('service_orders')
            ->join('users', 'users.id', '=', 'service_orders.technician_id')
            ->whereNotNull('service_orders.technician_id')
            ->whereNull('service_orders.deleted_at')
            ->select('users.name', DB::raw('COUNT(*) as total'))
            ->groupBy('users.id', 'users.name')
            ->orderByDesc('total')
            ->first();

        $stats['top_technician_name'] = $topTechnician->name ?? '-';
        $stats['top_technician_count'] = (int) ($topTechnician->total ?? 0);

        $recentOrders = DB::table('service_orders')
            ->join('customers', 'customers.id', '=', 'service_orders.customer_id')
            ->whereNull('service_orders.deleted_at')
            ->select('service_orders.*', 'customers.name as customer_name')
            ->orderByDesc('service_orders.created_at')
            ->limit(10)
            ->get()
            ->map(function ($row) {
                $row->status_label = ServiceOrderStatus::tryFrom($row->status)?->label() ?? $row->status;
                $row->status_color = ServiceOrderStatus::tryFrom($row->status)?->color() ?? 'secondary';

                return $row;
            });

        $months = collect(range(11, 0))->map(fn (int $i) => now()->subMonths($i)->format('Y-m'));

        $serviceByMonth = DB::table('service_orders')
            ->select(DB::raw('DATE_FORMAT(entry_date, "%Y-%m") as month'), DB::raw('COUNT(*) as total'))
            ->whereDate('entry_date', '>=', now()->subMonths(11)->startOfMonth())
            ->whereNull('deleted_at')
            ->groupBy('month')
            ->pluck('total', 'month');

        $revenueByMonth = DB::table('payments')
            ->select(DB::raw('DATE_FORMAT(payment_date, "%Y-%m") as month'), DB::raw('SUM(amount) as total'))
            ->whereDate('payment_date', '>=', now()->subMonths(11)->startOfMonth())
            ->groupBy('month')
            ->pluck('total', 'month');

        $sparepartUsage = DB::table($itemsTable)
            ->join($spareTable, "{$spareTable}.id", '=', "{$itemsTable}.{$spareFk}")
            ->join('service_orders', 'service_orders.id', '=', "{$itemsTable}.service_order_id")
            ->whereBetween('service_orders.entry_date', [$startOfMonth, $endOfMonth])
            ->select("{$spareTable}.name", DB::raw("SUM({$itemsTable}.quantity) as qty"))
            ->groupBy("{$spareTable}.id", "{$spareTable}.name")
            ->orderByDesc('qty')
            ->limit(8)
            ->get();

        return [
            'stats' => $stats,
            'recentOrders' => $recentOrders,
            'charts' => [
                'month_labels' => $months->map(fn (string $m) => Carbon::createFromFormat('Y-m', $m)->translatedFormat('M Y'))->values()->all(),
                'service_monthly' => $months->map(fn (string $m) => (int) ($serviceByMonth[$m] ?? 0))->values()->all(),
                'revenue_monthly' => $months->map(fn (string $m) => (float) ($revenueByMonth[$m] ?? 0))->values()->all(),
                'sparepart_labels' => $sparepartUsage->pluck('name')->all(),
                'sparepart_qty' => $sparepartUsage->pluck('qty')->map(fn ($q) => (int) $q)->all(),
            ],
        ];
    }
}
