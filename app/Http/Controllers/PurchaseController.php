<?php

namespace App\Http\Controllers;

use App\Exports\PurchaseExport;
use App\Http\Controllers\Concerns\ExportsResource;
use App\Http\Requests\PurchaseStoreRequest;
use App\Http\Requests\PurchaseUpdateRequest;
use App\Models\Purchase;
use App\Models\SparePart;
use App\Models\Supplier;
use App\Services\PurchaseService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;

class PurchaseController extends Controller
{
    use ExportsResource;

    public function __construct(
        private readonly PurchaseService $purchaseService,
    ) {}

    public function index(Request $request): View
    {
        $filters = $request->only(['search', 'supplier_id', 'date_from', 'date_to']);

        $items = Purchase::query()
            ->with(['supplier', 'user'])
            ->filter($filters)
            ->latest('purchase_date')
            ->latest('id')
            ->paginate(15)
            ->appends($filters);

        $suppliers = Supplier::query()->orderBy('name')->get();

        return view('purchases.index', ['purchases' => $items, 'filters' => $filters, 'suppliers' => $suppliers]);
    }

    public function create(): View
    {
        $suppliers = Supplier::query()->orderBy('name')->get();
        $spareParts = SparePart::query()->orderBy('name')->get();

        return view('purchases.create', compact('suppliers', 'spareParts'));
    }

    public function store(PurchaseStoreRequest $request): RedirectResponse
    {
        $data = $request->safe()->except('items');
        $items = $request->validated('items');

        $purchase = $this->purchaseService->create($data, $items, $request->user()->id);

        return redirect()
            ->route('purchases.show', $purchase)
            ->with('success', 'Pembelian berhasil dicatat.');
    }

    public function show(Purchase $purchase): View
    {
        $purchase->load(['items.sparePart', 'supplier', 'user']);

        return view('purchases.show', compact('purchase'));
    }

    public function edit(Purchase $purchase): View
    {
        $suppliers = Supplier::query()->orderBy('name')->get();

        return view('purchases.edit', compact('purchase', 'suppliers'));
    }

    public function update(PurchaseUpdateRequest $request, Purchase $purchase): RedirectResponse
    {
        $purchase->update($request->validated());

        return redirect()
            ->route('purchases.show', $purchase)
            ->with('success', 'Pembelian berhasil diperbarui.');
    }

    public function destroy(Purchase $purchase): RedirectResponse
    {
        $purchase->delete();

        return redirect()
            ->route('purchases.index')
            ->with('success', 'Pembelian berhasil dihapus.');
    }

    public function exportExcel(Request $request): BinaryFileResponse
    {
        $filters = $request->only(['search', 'supplier_id', 'date_from', 'date_to']);

        return $this->exportExcel(PurchaseExport::class, $filters, 'pembelian-'.now()->format('Ymd-His').'.xlsx');
    }

    public function exportPdf(Request $request): Response
    {
        $filters = $request->only(['search', 'supplier_id', 'date_from', 'date_to']);
        $items = (new PurchaseExport($filters))->query()->get();

        return $this->exportPdf(
            'reports.pdf.purchase',
            compact('items', 'filters'),
            'pembelian-'.now()->format('Ymd-His').'.pdf'
        );
    }

    public function print(Request $request): Response
    {
        $filters = $request->only(['search', 'supplier_id', 'date_from', 'date_to']);
        $items = (new PurchaseExport($filters))->query()->get();

        return $this->printView('reports.pdf.purchase', compact('items', 'filters'));
    }
}
