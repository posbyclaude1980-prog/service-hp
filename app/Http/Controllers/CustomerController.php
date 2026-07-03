<?php

namespace App\Http\Controllers;

use App\Exports\CustomerExport;
use App\Http\Controllers\Concerns\ExportsResource;
use App\Http\Requests\CustomerStoreRequest;
use App\Http\Requests\CustomerUpdateRequest;
use App\Models\Customer;
use App\Services\CodeGeneratorService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;

class CustomerController extends Controller
{
    use ExportsResource;

    public function __construct(
        private readonly CodeGeneratorService $codeGeneratorService,
    ) {}

    public function index(Request $request): View
    {
        $filters = $request->only(['search']);

        $items = Customer::query()
            ->when(
                $filters['search'] ?? null,
                fn ($q, string $search) => $q->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                })
            )
            ->latest()
            ->paginate(15)
            ->appends($filters);

        return view('customers.index', ['customers' => $items, 'filters' => $filters]);
    }

    public function create(): View
    {
        return view('customers.create');
    }

    public function store(CustomerStoreRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['code'] = $this->codeGeneratorService->generateCode('CUS', Customer::class);

        Customer::query()->create($data);

        return redirect()
            ->route('customers.index')
            ->with('success', 'Pelanggan berhasil ditambahkan.');
    }

    public function show(Customer $customer): View
    {
        $customer->load(['serviceOrders' => fn ($q) => $q->latest('entry_date')->limit(10)]);

        return view('customers.show', compact('customer'));
    }

    public function edit(Customer $customer): View
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(CustomerUpdateRequest $request, Customer $customer): RedirectResponse
    {
        $customer->update($request->validated());

        return redirect()
            ->route('customers.index')
            ->with('success', 'Pelanggan berhasil diperbarui.');
    }

    public function destroy(Customer $customer): RedirectResponse
    {
        $customer->delete();

        return redirect()
            ->route('customers.index')
            ->with('success', 'Pelanggan berhasil dihapus.');
    }

    public function exportExcel(Request $request): BinaryFileResponse
    {
        $filters = $request->only(['search']);

        return $this->exportExcel(CustomerExport::class, $filters, 'pelanggan-'.now()->format('Ymd-His').'.xlsx');
    }

    public function exportPdf(Request $request): Response
    {
        $filters = $request->only(['search']);

        $items = (new CustomerExport($filters))->query()->get();

        return $this->exportPdf(
            'reports.pdf.customer',
            compact('items', 'filters'),
            'pelanggan-'.now()->format('Ymd-His').'.pdf'
        );
    }

    public function print(Request $request): Response
    {
        $filters = $request->only(['search']);
        $items = (new CustomerExport($filters))->query()->get();

        return $this->printView('reports.pdf.customer', compact('items', 'filters'));
    }
}
