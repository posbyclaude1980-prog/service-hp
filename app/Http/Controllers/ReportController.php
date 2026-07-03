<?php

namespace App\Http\Controllers;

use App\Exports\RevenueReportExport;
use App\Exports\ServiceReportExport;
use App\Exports\StockReportExport;
use App\Http\Controllers\Concerns\ExportsResource;
use App\Models\Payment;
use App\Models\ServiceOrder;
use App\Models\SparePart;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;

class ReportController extends Controller
{
    use ExportsResource;

    public function serviceReport(Request $request): View
    {
        $filters = $request->only(['search', 'status', 'technician_id', 'date_from', 'date_to']);
        $technicians = User::query()->whereRoleSlug('teknisi')->orderBy('name')->get();

        // #region agent log
        file_put_contents(
            base_path('debug-df97ec.log'),
            json_encode([
                'sessionId' => 'df97ec',
                'runId' => 'post-fix',
                'hypothesisId' => 'A',
                'location' => 'ReportController.php:serviceReport',
                'message' => 'Technicians loaded for service report',
                'data' => ['technicianCount' => $technicians->count()],
                'timestamp' => (int) round(microtime(true) * 1000),
            ], JSON_UNESCAPED_SLASHES)."\n",
            FILE_APPEND
        );
        // #endregion

        $baseQuery = ServiceOrder::query()
            ->with(['customer', 'technician', 'phoneModel'])
            ->filter($filters);

        $orders = (clone $baseQuery)
            ->latest('entry_date')
            ->paginate(15)
            ->appends($filters);

        $summary = [
            'total' => (clone $baseQuery)->count(),
            'in_progress' => (clone $baseQuery)->whereIn('status', [
                'received', 'diagnosis', 'waiting_approval', 'in_service', 'qc',
            ])->count(),
            'completed' => (clone $baseQuery)->whereIn('status', ['completed', 'picked_up', 'warranty', 'closed'])->count(),
            'total_value' => (float) (clone $baseQuery)->sum('total_cost'),
        ];

        return view('reports.service', compact('orders', 'filters', 'technicians', 'summary'));
    }

    public function revenueReport(Request $request): View
    {
        $filters = $request->only(['date_from', 'date_to', 'method']);
        $dateFrom = $filters['date_from'] ?? null;
        $dateTo = $filters['date_to'] ?? null;

        $query = (new RevenueReportExport($filters))->query()->with(['serviceOrder.customer', 'user']);

        $payments = (clone $query)
            ->latest('payment_date')
            ->paginate(15)
            ->appends($filters);

        $total = (float) (clone $query)->sum('amount');
        $count = (clone $query)->count();

        $summary = [
            'total_revenue' => $total,
            'total_transactions' => $count,
            'average' => $count > 0 ? $total / $count : 0,
        ];

        $byMethod = $this->aggregateRevenueByMethod(clone $query);

        $chartRows = Payment::query()
            ->select(DB::raw('DATE(payment_date) as day'), DB::raw('SUM(amount) as total'))
            ->when($dateFrom, fn ($q) => $q->whereDate('payment_date', '>=', $dateFrom))
            ->when($dateTo, fn ($q) => $q->whereDate('payment_date', '<=', $dateTo))
            ->when($filters['method'] ?? null, function ($q, string $method): void {
                if (Schema::hasColumn('payments', 'method')) {
                    $q->where('method', $method);

                    return;
                }

                $q->whereHas('paymentMethod', fn ($subQ) => $subQ->where('code', $method));
            })
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        // #region agent log
        file_put_contents(
            base_path('debug-df97ec.log'),
            json_encode([
                'sessionId' => 'df97ec',
                'runId' => 'post-fix-revenue',
                'hypothesisId' => 'B',
                'location' => 'ReportController.php:revenueReport',
                'message' => 'Revenue report aggregated by payment method',
                'data' => [
                    'usesLegacyMethodColumn' => Schema::hasColumn('payments', 'method'),
                    'methodCount' => count($byMethod),
                    'paymentCount' => $count,
                ],
                'timestamp' => (int) round(microtime(true) * 1000),
            ], JSON_UNESCAPED_SLASHES)."\n",
            FILE_APPEND
        );
        // #endregion

        $chartLabels = $chartRows->map(fn ($r) => \Carbon\Carbon::parse($r->day)->format('d/m'))->all();
        $chartData = $chartRows->pluck('total')->map(fn ($v) => (float) $v)->all();

        return view('reports.revenue', compact('payments', 'filters', 'summary', 'byMethod', 'chartLabels', 'chartData', 'dateFrom', 'dateTo'));
    }

    public function stockReport(Request $request): View
    {
        $filters = $request->only(['search', 'status']);
        $query = (new StockReportExport($this->mapStockFilters($filters)))->query();

        $spareParts = (clone $query)
            ->orderBy('name')
            ->paginate(15)
            ->appends($filters);

        $all = (clone $query);

        $summary = [
            'total_items' => (clone $all)->count(),
            'low_stock' => (clone $all)->lowStock()->count(),
            'out_of_stock' => (clone $all)->where('stock', '<=', 0)->count(),
            'stock_value' => (float) (clone $all)->selectRaw('SUM(stock * purchase_price) as value')->value('value'),
        ];

        return view('reports.stock', compact('spareParts', 'filters', 'summary'));
    }

  /** @param array<string, mixed> $filters */
    private function mapStockFilters(array $filters): array
    {
        $mapped = ['search' => $filters['search'] ?? null];

        if (($filters['status'] ?? '') === 'low') {
            $mapped['low_stock'] = '1';
        }

        if (($filters['status'] ?? '') === 'out') {
            $mapped['out_of_stock'] = '1';
        }

        return $mapped;
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder<\App\Models\Payment>  $query
     * @return array<string, float>
     */
    private function aggregateRevenueByMethod($query): array
    {
        if (Schema::hasColumn('payments', 'method')) {
            return $query
                ->reorder()
                ->select('method', DB::raw('SUM(amount) as total'))
                ->groupBy('method')
                ->pluck('total', 'method')
                ->map(fn ($v) => (float) $v)
                ->all();
        }

        return $query
            ->reorder()
            ->join('payment_methods', 'payment_methods.id', '=', 'payments.payment_method_id')
            ->select('payment_methods.code as method', DB::raw('SUM(payments.amount) as total'))
            ->groupBy('payment_methods.code')
            ->pluck('total', 'method')
            ->map(fn ($v) => (float) $v)
            ->all();
    }

    public function exportServiceExcel(Request $request): BinaryFileResponse
    {
        $filters = $request->only(['search', 'status', 'technician_id', 'date_from', 'date_to']);

        return $this->exportExcel(ServiceReportExport::class, $filters, 'laporan-servis-'.now()->format('Ymd-His').'.xlsx');
    }

    public function exportServicePdf(Request $request): Response
    {
        $filters = $request->only(['search', 'status', 'technician_id', 'date_from', 'date_to']);
        $items = (new ServiceReportExport($filters))->query()->get();

        return $this->exportPdf(
            'reports.pdf.service-report',
            compact('items', 'filters'),
            'laporan-servis-'.now()->format('Ymd-His').'.pdf',
            'a4',
            'landscape'
        );
    }

    public function printService(Request $request): Response
    {
        $filters = $request->only(['search', 'status', 'technician_id', 'date_from', 'date_to']);
        $items = (new ServiceReportExport($filters))->query()->get();

        return $this->printView('reports.pdf.service-report', compact('items', 'filters'));
    }

    public function exportRevenueExcel(Request $request): BinaryFileResponse
    {
        $filters = $request->only(['date_from', 'date_to', 'method']);

        return $this->exportExcel(RevenueReportExport::class, $filters, 'laporan-pendapatan-'.now()->format('Ymd-His').'.xlsx');
    }

    public function exportRevenuePdf(Request $request): Response
    {
        $filters = $request->only(['date_from', 'date_to', 'method']);
        $items = (new RevenueReportExport($filters))->query()->with(['serviceOrder.customer', 'user'])->get();

        return $this->exportPdf(
            'reports.revenue-pdf',
            ['payments' => $items, 'filters' => $filters],
            'laporan-pendapatan-'.now()->format('Ymd-His').'.pdf',
            'a4',
            'landscape'
        );
    }

    public function printRevenue(Request $request): Response
    {
        $filters = $request->only(['date_from', 'date_to', 'method']);
        $items = (new RevenueReportExport($filters))->query()->with(['serviceOrder.customer', 'user'])->get();

        return $this->printView('reports.revenue-pdf', ['payments' => $items, 'filters' => $filters]);
    }

    public function exportStockExcel(Request $request): BinaryFileResponse
    {
        $filters = $this->mapStockFilters($request->only(['search', 'status']));

        return $this->exportExcel(StockReportExport::class, $filters, 'laporan-stok-'.now()->format('Ymd-His').'.xlsx');
    }

    public function exportStockPdf(Request $request): Response
    {
        $filters = $this->mapStockFilters($request->only(['search', 'status']));
        $items = (new StockReportExport($filters))->query()->get();

        return $this->exportPdf(
            'reports.stock-pdf',
            ['spareParts' => $items, 'filters' => $filters],
            'laporan-stok-'.now()->format('Ymd-His').'.pdf',
            'a4',
            'landscape'
        );
    }

    public function printStock(Request $request): Response
    {
        $filters = $this->mapStockFilters($request->only(['search', 'status']));
        $items = (new StockReportExport($filters))->query()->get();

        return $this->printView('reports.stock-pdf', ['spareParts' => $items, 'filters' => $filters]);
    }
}
