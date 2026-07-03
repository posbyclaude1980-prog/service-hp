<?php

namespace App\Http\Controllers;

use App\Exports\SupplierExport;
use App\Http\Controllers\Concerns\ExportsResource;
use App\Http\Requests\SupplierStoreRequest;
use App\Http\Requests\SupplierUpdateRequest;
use App\Models\Supplier;
use App\Services\CodeGeneratorService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;

class SupplierController extends Controller
{
    use ExportsResource;

    public function __construct(
        private readonly CodeGeneratorService $codeGeneratorService,
    ) {}

    public function index(Request $request): View
    {
        $filters = $request->only(['search']);

        $items = Supplier::query()
            ->filter($filters)
            ->latest()
            ->paginate(15)
            ->appends($filters);

        return view('suppliers.index', ['suppliers' => $items, 'filters' => $filters]);
    }

    public function create(): View
    {
        return view('suppliers.create');
    }

    public function store(SupplierStoreRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['code'] = $this->codeGeneratorService->generateCode('SUP', Supplier::class);

        Supplier::query()->create($data);

        return redirect()
            ->route('suppliers.index')
            ->with('success', 'Supplier berhasil ditambahkan.');
    }

    public function show(Supplier $supplier): View
    {
        $supplier->load(['purchases' => fn ($q) => $q->latest('purchase_date')->limit(10)]);

        return view('suppliers.show', compact('supplier'));
    }

    public function edit(Supplier $supplier): View
    {
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(SupplierUpdateRequest $request, Supplier $supplier): RedirectResponse
    {
        $supplier->update($request->validated());

        return redirect()
            ->route('suppliers.index')
            ->with('success', 'Supplier berhasil diperbarui.');
    }

    public function destroy(Supplier $supplier): RedirectResponse
    {
        $supplier->delete();

        return redirect()
            ->route('suppliers.index')
            ->with('success', 'Supplier berhasil dihapus.');
    }

    public function exportExcel(Request $request): BinaryFileResponse
    {
        $filters = $request->only(['search']);

        return $this->exportExcel(SupplierExport::class, $filters, 'supplier-'.now()->format('Ymd-His').'.xlsx');
    }

    public function exportPdf(Request $request): Response
    {
        $filters = $request->only(['search']);
        $items = (new SupplierExport($filters))->query()->get();

        return $this->exportPdf(
            'reports.pdf.supplier',
            compact('items', 'filters'),
            'supplier-'.now()->format('Ymd-His').'.pdf'
        );
    }

    public function print(Request $request): Response
    {
        $filters = $request->only(['search']);
        $items = (new SupplierExport($filters))->query()->get();

        return $this->printView('reports.pdf.supplier', compact('items', 'filters'));
    }
}
