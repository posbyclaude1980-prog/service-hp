<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentStoreRequest;
use App\Models\Payment;
use App\Models\ServiceOrder;
use App\Repositories\Contracts\ServiceOrderRepositoryInterface;
use App\Services\PaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function __construct(
        private readonly ServiceOrderRepositoryInterface $serviceOrderRepository,
        private readonly PaymentService $paymentService,
    ) {}

    public function index(Request $request): View
    {
        $filters = $request->only(['service_order_id', 'date_from', 'date_to']);

        $items = Payment::query()
            ->with(['serviceOrder.customer', 'user', 'paymentMethod'])
            ->when(
                $filters['service_order_id'] ?? null,
                fn ($q, $id) => $q->where('service_order_id', $id)
            )
            ->when(
                $filters['date_from'] ?? null,
                fn ($q, string $date) => $q->whereDate('payment_date', '>=', $date)
            )
            ->when(
                $filters['date_to'] ?? null,
                fn ($q, string $date) => $q->whereDate('payment_date', '<=', $date)
            )
            ->latest('payment_date')
            ->latest('id')
            ->paginate(15)
            ->appends($filters);

        return view('payments.index', ['payments' => $items, 'filters' => $filters]);
    }

    public function create(Request $request): View
    {
        $serviceOrderId = $request->query('service_order_id');
        $serviceOrder = $serviceOrderId
            ? $this->serviceOrderRepository->findWithRelations((int) $serviceOrderId, ['customer'])
            : null;

        $serviceOrders = ServiceOrder::query()
            ->with('customer')
            ->whereNotIn('status', ['cancelled', 'picked_up'])
            ->latest('entry_date')
            ->get();

        return view('payments.create', compact('serviceOrder', 'serviceOrders'));
    }

    public function store(PaymentStoreRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $result = $this->paymentService->record($data, $request->user());

        $redirect = redirect()->route('service-orders.show', $data['service_order_id']);

        $redirect = $redirect->with('payment_created_id', $result['payment']->id);

        if ($result['invoice'] !== null) {
            return $redirect->with('success', 'Pembayaran lunas. Invoice PDF telah digenerate otomatis.')
                ->with('invoice_generated', $result['invoice']->id);
        }

        return $redirect->with('success', 'Pembayaran berhasil dicatat.');
    }

    public function destroy(Payment $payment): RedirectResponse
    {
        $serviceOrderId = $payment->service_order_id;

        $payment->delete();
        $this->paymentService->recalculatePaidAmount($serviceOrderId);

        return redirect()
            ->route('service-orders.show', $serviceOrderId)
            ->with('success', 'Pembayaran berhasil dihapus.');
    }
}
