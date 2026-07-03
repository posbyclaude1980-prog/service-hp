<?php

namespace App\Http\Controllers;

use App\Repositories\Contracts\ServiceOrderRepositoryInterface;
use App\Services\DotMatrixPrintService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PrintController extends Controller
{
    public function __construct(
        private readonly DotMatrixPrintService $printService,
        private readonly ServiceOrderRepositoryInterface $serviceOrderRepository,
    ) {}

    public function index(): Response
    {
        return response()->view('prints.index', [
            'settings' => $this->printService->context(request())['settings'],
        ]);
    }

    public function receipt(Request $request, int $service_order): Response
    {
        return $this->renderOrderTemplate($request, $service_order, 'prints.receipt', 'Bukti Penerimaan');
    }

    public function invoice(Request $request, int $service_order): Response
    {
        return $this->renderOrderTemplate($request, $service_order, 'prints.invoice', 'Invoice');
    }

    public function cashierReceipt(Request $request, int $payment): Response
    {
        $ctx = $this->printService->context($request);
        $payment = Payment::query()
            ->with(['serviceOrder.customer', 'serviceOrder.technician.user'])
            ->findOrFail($payment);

        return response()->view('prints.cashier-receipt', array_merge($ctx, [
            'payment' => $payment,
            'serviceOrder' => $payment->serviceOrder,
            'title' => 'Nota Kasir',
        ]));
    }

    public function warranty(Request $request, int $service_order): Response
    {
        return $this->renderOrderTemplate($request, $service_order, 'prints.warranty-card', 'Kartu Garansi');
    }

    public function cashierReceiptForm(Request $request): Response
    {
        $ctx = $this->printService->context($request);

        return response()->view('prints.cashier-receipt-form', array_merge($ctx, [
            'title' => 'Input Kwitansi',
        ]));
    }

    public function cashierReceiptPreview(Request $request): Response
    {
        $ctx = $this->printService->context($request);

        $data = $request->validate([
            'nota_service' => ['required', 'string', 'max:60'],
            'nota_number' => ['required', 'string', 'max:60'],
            'description' => ['nullable', 'string', 'max:500'],
            'brand' => ['nullable', 'string', 'max:80'],
            'type' => ['nullable', 'string', 'max:80'],
            'tanggal' => ['required', 'date'],
            'nama' => ['required', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:30'],
            'service_fee' => ['required', 'numeric', 'min:0'],
            'warranty' => ['required', 'in:garansi,tidak'],
        ]);

        return response()->view('prints.cashier-receipt-custom', array_merge($ctx, $data, [
            'title' => 'Preview Kwitansi',
        ]));
    }

    public function label(Request $request, int $service_order): Response
    {
        if (! $request->has('paper')) {
            $request->merge(['paper' => 'continuous']);
        }

        $ctx = $this->printService->context($request);
        $order = $this->loadOrder($service_order, ['payments']);

        return response()->view('prints.service-label', array_merge($ctx, [
            'serviceOrder' => $order,
            'title' => 'Label Service',
        ]));
    }

    public function dailyReport(Request $request): Response
    {
        $ctx = $this->printService->context($request);
        $date = Carbon::parse($request->query('date', now()->toDateString()));
        $report = $this->printService->dailyReportData($date);

        return response()->view('prints.daily-report', array_merge($ctx, $report, [
            'title' => 'Laporan Harian',
        ]));
    }

    public function monthlyReport(Request $request): Response
    {
        $ctx = $this->printService->context($request);
        $year = (int) $request->query('year', now()->year);
        $month = (int) $request->query('month', now()->month);
        $report = $this->printService->monthlyReportData($year, $month);

        return response()->view('prints.monthly-report', array_merge($ctx, $report, [
            'title' => 'Laporan Bulanan',
        ]));
    }

    private function renderOrderTemplate(Request $request, int $serviceOrderId, string $view, string $title): Response
    {
        $ctx = $this->printService->context($request);
        $order = $this->loadOrder($serviceOrderId, ['payments', 'items.sparePart']);

        return response()->view($view, array_merge($ctx, [
            'serviceOrder' => $order,
            'title' => $title,
        ]));
    }

    /**
     * @param  array<int, string>  $extraRelations
     */
    private function loadOrder(int $id, array $extraRelations = []): \App\Models\ServiceOrder
    {
        $relations = array_merge(['customer', 'technician.user', 'intakeImages'], $extraRelations);
        $order = $this->serviceOrderRepository->findWithRelations($id, $relations);

        abort_if($order === null, 404);

        return $order;
    }
}
