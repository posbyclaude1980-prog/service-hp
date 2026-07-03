<?php

namespace App\Http\Controllers;

use App\Exports\SparePartExport;
use App\Http\Controllers\Concerns\ExportsResource;
use App\Http\Requests\SparePartStoreRequest;
use App\Http\Requests\SparePartUpdateRequest;
use App\Models\SparePart;
use App\Repositories\Contracts\SparePartRepositoryInterface;
use App\Services\CodeGeneratorService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;

class SparePartController extends Controller
{
    use ExportsResource;

    public function __construct(
        private readonly SparePartRepositoryInterface $sparePartRepository,
        private readonly CodeGeneratorService $codeGeneratorService,
    ) {}

    public function index(Request $request): View
    {
        $filters = $request->only(['search', 'low_stock', 'out_of_stock']);

        $items = $this->sparePartRepository
            ->paginateWithFilters($filters, 15)
            ->appends($filters);

        return view('spare-parts.index', ['spareParts' => $items, 'filters' => $filters]);
    }

    public function create(): View
    {
        return view('spare-parts.create');
    }

    public function store(SparePartStoreRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['code'] = $this->codeGeneratorService->generateCode('SPR', SparePart::class);

        SparePart::query()->create($data);

        return redirect()
            ->route('spare-parts.index')
            ->with('success', 'Sparepart berhasil ditambahkan.');
    }

    public function show(SparePart $sparePart): View
    {
        $sparePart->load(['stockMutations' => fn ($q) => $q->latest()->limit(20)]);

        return view('spare-parts.show', compact('sparePart'));
    }

    public function edit(SparePart $sparePart): View
    {
        return view('spare-parts.edit', compact('sparePart'));
    }

    public function update(SparePartUpdateRequest $request, SparePart $sparePart): RedirectResponse
    {
        $sparePart->update($request->validated());

        return redirect()
            ->route('spare-parts.index')
            ->with('success', 'Sparepart berhasil diperbarui.');
    }

    public function destroy(SparePart $sparePart): RedirectResponse
    {
        $sparePart->delete();

        return redirect()
            ->route('spare-parts.index')
            ->with('success', 'Sparepart berhasil dihapus.');
    }

    public function exportExcel(Request $request): BinaryFileResponse
    {
        $filters = $request->only(['search', 'low_stock', 'out_of_stock']);

        return $this->exportExcel(SparePartExport::class, $filters, 'sparepart-'.now()->format('Ymd-His').'.xlsx');
    }

    public function exportPdf(Request $request): Response
    {
        $filters = $request->only(['search', 'low_stock', 'out_of_stock']);
        $items = (new SparePartExport($filters))->query()->get();

        return $this->exportPdf(
            'reports.pdf.spare-part',
            compact('items', 'filters'),
            'sparepart-'.now()->format('Ymd-His').'.pdf'
        );
    }

    public function print(Request $request): Response
    {
        $filters = $request->only(['search', 'low_stock', 'out_of_stock']);
        $items = (new SparePartExport($filters))->query()->get();

        return $this->printView('reports.pdf.spare-part', compact('items', 'filters'));
    }
}
