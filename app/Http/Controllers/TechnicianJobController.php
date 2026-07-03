<?php

namespace App\Http\Controllers;

use App\Enums\ServiceOrderStatus;
use App\Http\Controllers\Concerns\AuthorizesTechnicianJob;
use App\Http\Requests\Technician\TechnicianDiagnosisRequest;
use App\Http\Requests\Technician\TechnicianNoteRequest;
use App\Http\Requests\Technician\TechnicianPhotoRequest;
use App\Http\Requests\Technician\TechnicianQcRequest;
use App\Http\Requests\Technician\TechnicianServiceFeeRequest;
use App\Http\Requests\Technician\TechnicianSparePartRequest;
use App\Http\Requests\Technician\TechnicianStatusRequest;
use App\Http\Requests\Technician\TechnicianVideoRequest;
use App\Models\ServiceOrder;
use App\Models\SparePart;
use App\Repositories\Contracts\ServiceOrderRepositoryInterface;
use App\Services\ServiceOrderStatusHistoryService;
use App\Services\TechnicianWorkService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TechnicianJobController extends Controller
{
    use AuthorizesTechnicianJob;

    public function __construct(
        private readonly ServiceOrderRepositoryInterface $serviceOrderRepository,
        private readonly TechnicianWorkService $workService,
        private readonly ServiceOrderStatusHistoryService $statusHistoryService,
    ) {}

    public function index(Request $request): View
    {
        $user = $request->user();
        abort_if($user === null, 403);

        $filters = $request->only(['search', 'status', 'tab']);
        $technicianId = $this->technicianIdForUser($user);

        $query = ServiceOrder::query()
            ->with(['customer', 'technician.user'])
            ->when(
                $user->role === 'teknisi' && $technicianId !== null,
                fn ($q) => $q->where('technician_id', $technicianId)
            )
            ->when(
                $user->role === 'teknisi' && $technicianId === null,
                fn ($q) => $q->whereRaw('1 = 0')
            )
            ->when(
                ($filters['tab'] ?? 'active') === 'active',
                fn ($q) => $q->whereNotIn('status', [
                    ServiceOrderStatus::Closed->value,
                    ServiceOrderStatus::Cancelled->value,
                    ServiceOrderStatus::PickedUp->value,
                ])
            )
            ->when(
                ($filters['tab'] ?? '') === 'completed',
                fn ($q) => $q->whereIn('status', [
                    ServiceOrderStatus::Completed->value,
                    ServiceOrderStatus::PickedUp->value,
                    ServiceOrderStatus::Closed->value,
                ])
            )
            ->when(
                $filters['status'] ?? null,
                fn ($q, string $status) => $q->where('status', $status)
            )
            ->when(
                $filters['search'] ?? null,
                fn ($q, string $search) => $q->where(function ($builder) use ($search): void {
                    $builder->where('ticket_number', 'like', "%{$search}%")
                        ->orWhere('device_brand', 'like', "%{$search}%")
                        ->orWhere('device_model', 'like', "%{$search}%")
                        ->orWhereHas('customer', fn ($c) => $c->where('name', 'like', "%{$search}%"));
                })
            )
            ->latest('entry_date')
            ->latest('id');

        $jobs = $query->paginate(15)->appends($filters);

        $stats = [
            'active' => ServiceOrder::query()
                ->when($technicianId, fn ($q) => $q->where('technician_id', $technicianId))
                ->whereNotIn('status', [ServiceOrderStatus::Closed->value, ServiceOrderStatus::Cancelled->value])
                ->count(),
            'qc' => ServiceOrder::query()
                ->when($technicianId, fn ($q) => $q->where('technician_id', $technicianId))
                ->where('status', ServiceOrderStatus::Qc->value)
                ->count(),
        ];

        return view('technician.jobs.index', compact('jobs', 'filters', 'stats', 'user'));
    }

    public function show(Request $request, int $service_order): View
    {
        $user = $request->user();
        abort_if($user === null, 403);

        $order = $this->loadWorkOrder($service_order);
        $this->authorizeTechnicianJob($order, $user);

        $allowedStatuses = $this->statusHistoryService->allowedNextStatuses($order, $user);
        $spareParts = SparePart::query()->where('is_active', true)->orderBy('name')->get();

        return view('technician.jobs.show', compact('order', 'allowedStatuses', 'spareParts', 'user'));
    }

    public function diagnosis(TechnicianDiagnosisRequest $request, int $service_order): RedirectResponse
    {
        $user = $request->user();
        $order = $this->loadWorkOrder($service_order);
        $this->authorizeTechnicianJob($order, $user);

        $this->workService->saveDiagnosis(
            $order,
            $user,
            $request->validated('diagnosis'),
            $request->validated('notes'),
        );

        return back()->with('success', 'Diagnosa berhasil disimpan.');
    }

    public function updateStatus(TechnicianStatusRequest $request, int $service_order): RedirectResponse
    {
        $user = $request->user();
        $order = $this->loadWorkOrder($service_order);
        $this->authorizeTechnicianJob($order, $user);

        $validated = $request->validated();

        $this->workService->updateStatus(
            $order,
            $user,
            $validated['status'],
            $validated['notes'],
            $request->file('photo'),
            $validated['diagnosis'] ?? null,
        );

        return back()->with('success', 'Status berhasil diperbarui.');
    }

    public function spareParts(TechnicianSparePartRequest $request, int $service_order): RedirectResponse
    {
        $user = $request->user();
        $order = $this->loadWorkOrder($service_order);
        $this->authorizeTechnicianJob($order, $user);

        $this->workService->syncSpareParts(
            $order,
            $user,
            $request->validated('items'),
            $request->validated('notes'),
        );

        return back()->with('success', 'Spare part berhasil diperbarui.');
    }

    public function serviceFee(TechnicianServiceFeeRequest $request, int $service_order): RedirectResponse
    {
        $user = $request->user();
        $order = $this->loadWorkOrder($service_order);
        $this->authorizeTechnicianJob($order, $user);

        $this->workService->updateServiceFee(
            $order,
            $user,
            (float) $request->validated('service_fee'),
            $request->validated('notes'),
        );

        return back()->with('success', 'Biaya jasa berhasil diperbarui.');
    }

    public function photo(TechnicianPhotoRequest $request, int $service_order): RedirectResponse
    {
        $user = $request->user();
        $order = $this->loadWorkOrder($service_order);
        $this->authorizeTechnicianJob($order, $user);

        $this->workService->uploadPhoto(
            $order,
            $user,
            $request->file('photo'),
            $request->validated('caption'),
            $request->validated('notes'),
        );

        return back()->with('success', 'Foto berhasil diunggah.');
    }

    public function video(TechnicianVideoRequest $request, int $service_order): RedirectResponse
    {
        $user = $request->user();
        $order = $this->loadWorkOrder($service_order);
        $this->authorizeTechnicianJob($order, $user);

        $this->workService->uploadVideo(
            $order,
            $user,
            $request->file('video'),
            $request->validated('caption'),
            $request->validated('notes'),
        );

        return back()->with('success', 'Video berhasil diunggah.');
    }

    public function note(TechnicianNoteRequest $request, int $service_order): RedirectResponse
    {
        $user = $request->user();
        $order = $this->loadWorkOrder($service_order);
        $this->authorizeTechnicianJob($order, $user);

        $this->workService->addNote($order, $user, $request->validated('notes'));

        return back()->with('success', 'Catatan berhasil ditambahkan.');
    }

    public function qc(TechnicianQcRequest $request, int $service_order): RedirectResponse
    {
        $user = $request->user();
        $order = $this->loadWorkOrder($service_order);
        $this->authorizeTechnicianJob($order, $user);

        $passed = $request->validated('qc_result') === 'pass';

        $this->workService->performQc($order, $user, $passed, $request->validated('notes'));

        return back()->with('success', $passed ? 'QC lulus — pekerjaan selesai.' : 'QC gagal — dikembalikan ke proses service.');
    }

    private function loadWorkOrder(int $id): ServiceOrder
    {
        $order = $this->serviceOrderRepository->findWithRelations($id, [
            'items',
            'workLogs.user',
            'workImages',
            'workVideos',
            'statusHistories.user',
            'intakeImages',
        ]);

        abort_if($order === null, 404);

        return $order;
    }
}
