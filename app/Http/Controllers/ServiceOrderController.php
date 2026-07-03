<?php

namespace App\Http\Controllers;

use App\Enums\ServiceOrderStatus;
use App\Exports\ServiceOrderExport;
use App\Http\Controllers\Concerns\ExportsResource;
use App\Http\Requests\ServiceOrderStatusUpdateRequest;
use App\Http\Requests\ServiceOrderStoreRequest;
use App\Http\Requests\ServiceOrderUpdateRequest;
use App\Models\Brand;
use App\Models\Customer;
use App\Models\PhoneModel;
use App\Models\ServiceOrder;
use App\Models\SparePart;
use App\Models\Technician;
use App\Repositories\Contracts\ServiceOrderRepositoryInterface;
use App\Services\ServiceOrderPhotoService;
use App\Services\ServiceOrderService;
use App\Services\ServiceOrderStatusHistoryService;
use App\Services\SettingService;
use App\Services\TicketNumberService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;

class ServiceOrderController extends Controller
{
    use ExportsResource {
        exportPdf as exportPdfView;
    }

    public function __construct(
        private readonly ServiceOrderRepositoryInterface $serviceOrderRepository,
        private readonly ServiceOrderService $serviceOrderService,
        private readonly ServiceOrderStatusHistoryService $statusHistoryService,
        private readonly SettingService $settingService,
        private readonly TicketNumberService $ticketNumberService,
    ) {}

    public function index(Request $request): View
    {
        $filters = $request->only(['search', 'status', 'technician_id', 'date_from', 'date_to', 'customer_id']);

        $items = $this->serviceOrderRepository
            ->paginateWithFilters($filters, 15)
            ->appends($filters);

        $technicians = Technician::query()
            ->with('user')
            ->where('is_active', true)
            ->get()
            ->sortBy(fn (Technician $t) => $t->user?->name);

        return view('service-orders.index', ['serviceOrders' => $items, 'filters' => $filters, 'technicians' => $technicians]);
    }

    public function create(): View
    {
        $customers = Customer::query()->orderBy('name')->get();
        $brands = Brand::query()->where('is_active', true)->orderBy('name')->get();
        $phoneModels = PhoneModel::query()->with('brand')->where('is_active', true)->orderBy('name')->get();
        $technicians = Technician::query()
            ->with('user')
            ->where('is_active', true)
            ->get()
            ->sortBy(fn (Technician $t) => $t->user?->name);
        $previewTicketNumber = $this->ticketNumberService->preview();
        $statuses = [ServiceOrderStatus::Received];

        return view('service-orders.create', compact(
            'customers',
            'brands',
            'phoneModels',
            'technicians',
            'previewTicketNumber',
            'statuses',
        ));
    }

    public function store(ServiceOrderStoreRequest $request): RedirectResponse
    {
        $data = $request->safe()->except(array_keys(ServiceOrderPhotoService::INTAKE_FIELDS));
        $photos = $this->extractIntakePhotos($request);

        $order = $this->serviceOrderService->create($data, [], $request->user()?->id, $photos);

        return redirect()
            ->route('service-orders.show', $order)
            ->with('success', 'Penerimaan service HP berhasil disimpan.');
    }

    public function show(Request $request, int $service_order): View
    {
        $serviceOrder = $this->serviceOrderRepository->findWithRelations($service_order);

        abort_if($serviceOrder === null, 404);

        $serviceOrder->load(['payments.user', 'payments.paymentMethod', 'statusHistories.user', 'statusHistories.images', 'intakeImages', 'workLogs.user', 'workImages', 'workVideos', 'invoice']);

        $allowedStatuses = $this->statusHistoryService->allowedNextStatuses(
            $serviceOrder,
            $request->user(),
        );

        return view('service-orders.show', compact('serviceOrder', 'allowedStatuses'));
    }

    public function edit(int $service_order): View
    {
        $serviceOrder = $this->serviceOrderRepository->findWithRelations($service_order);

        abort_if($serviceOrder === null, 404);

        $serviceOrder->load('intakeImages');

        $customers = Customer::query()->orderBy('name')->get();
        $brands = Brand::query()->where('is_active', true)->orderBy('name')->get();
        $phoneModels = PhoneModel::query()->with('brand')->where('is_active', true)->orderBy('name')->get();
        $spareParts = SparePart::query()->where('stock', '>', 0)->where('is_active', true)->orderBy('name')->get();
        $technicians = Technician::query()
            ->with('user')
            ->where('is_active', true)
            ->get()
            ->sortBy(fn (Technician $t) => $t->user?->name);

        return view('service-orders.edit', compact(
            'serviceOrder',
            'customers',
            'brands',
            'phoneModels',
            'spareParts',
            'technicians',
        ));
    }

    public function update(ServiceOrderUpdateRequest $request, int $service_order): RedirectResponse
    {
        $data = $request->safe()->except(array_merge(['items'], array_keys(ServiceOrderPhotoService::INTAKE_FIELDS)));
        $items = $request->has('items') ? ($request->validated('items') ?? []) : null;
        $photos = $this->extractIntakePhotos($request);

        $order = $this->serviceOrderService->update(
            $service_order,
            $data,
            $items,
            $request->user()?->id,
            $photos,
        );

        return redirect()
            ->route('service-orders.show', $order)
            ->with('success', 'Data service HP berhasil diperbarui.');
    }

    public function destroy(int $service_order): RedirectResponse
    {
        $order = ServiceOrder::query()->findOrFail($service_order);
        $order->delete();

        return redirect()
            ->route('service-orders.index')
            ->with('success', 'Order servis berhasil dihapus.');
    }

    public function updateStatus(ServiceOrderStatusUpdateRequest $request, int $service_order): RedirectResponse
    {
        $validated = $request->validated();
        $status = $validated['status'];
        $notes = $validated['notes'];
        $photo = $request->file('photo');

        $order = $this->serviceOrderService->updateStatus(
            $service_order,
            $status instanceof ServiceOrderStatus ? $status->value : (string) $status,
            $request->user(),
            $notes,
            $photo,
        );

        if (! empty($validated['diagnosis'])) {
            $order->update(['diagnosis' => $validated['diagnosis']]);
        }

        return redirect()
            ->route('service-orders.show', $order)
            ->with('success', 'Status berhasil diubah menjadi '.$order->fresh()->status_label.'.');
    }

    public function exportExcel(Request $request): BinaryFileResponse
    {
        $filters = $request->only(['search', 'status', 'technician_id', 'date_from', 'date_to', 'customer_id']);

        return $this->exportExcel(ServiceOrderExport::class, $filters, 'order-servis-'.now()->format('Ymd-His').'.xlsx');
    }

    public function exportPdf(Request $request, int $service_order): Response
    {
        $order = $this->serviceOrderRepository->findWithRelations($service_order, ['payments', 'items.sparePart', 'invoice']);

        abort_if($order === null, 404);

        if ($order->invoice && $order->invoice->pdfExists()) {
            return Storage::disk('public')->download(
                $order->invoice->pdf_path,
                $order->invoice->invoice_number.'.pdf'
            );
        }

        $settings = $this->settingService->getAll();

        return $this->exportPdfView(
            'reports.pdf.service-order-invoice',
            ['serviceOrder' => $order, 'settings' => $settings],
            'invoice-'.$order->ticket_number.'.pdf',
            'a4',
            'portrait'
        );
    }

    public function printLabel(int $service_order): Response
    {
        return redirect()->route('print.service-orders.label', [
            'service_order' => $service_order,
            'preview' => 1,
            'paper' => 'continuous',
        ]);
    }

    public function print(Request $request): Response
    {
        $filters = $request->only(['search', 'status', 'technician_id', 'date_from', 'date_to', 'customer_id']);
        $items = (new ServiceOrderExport($filters))->query()->get();

        return $this->printView('reports.pdf.service-order', compact('items', 'filters'));
    }

    /**
     * @return array<string, \Illuminate\Http\UploadedFile|null>
     */
    private function extractIntakePhotos(Request $request): array
    {
        $photos = [];

        foreach (array_keys(ServiceOrderPhotoService::INTAKE_FIELDS) as $field) {
            $photos[$field] = $request->file($field);
        }

        return $photos;
    }
}
