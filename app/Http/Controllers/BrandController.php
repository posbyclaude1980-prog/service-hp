<?php

namespace App\Http\Controllers;

use App\Exports\BrandExport;
use App\Http\Controllers\Concerns\ExportsResource;
use App\Http\Requests\BrandStoreRequest;
use App\Http\Requests\BrandUpdateRequest;
use App\Models\Brand;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;

class BrandController extends Controller
{
    use ExportsResource;

    public function index(Request $request): View
    {
        $filters = $request->only(['search']);

        $items = Brand::query()
            ->withCount('phoneModels')
            ->when(
                $filters['search'] ?? null,
                fn ($q, string $search) => $q->where('name', 'like', "%{$search}%")
            )
            ->latest()
            ->paginate(15)
            ->appends($filters);

        return view('brands.index', ['brands' => $items, 'filters' => $filters]);
    }

    public function create(): View
    {
        return view('brands.create');
    }

    public function store(BrandStoreRequest $request): RedirectResponse
    {
        Brand::query()->create($request->validated());

        return redirect()
            ->route('brands.index')
            ->with('success', 'Merek berhasil ditambahkan.');
    }

    public function show(Brand $brand): View
    {
        $brand->load(['phoneModels']);

        return view('brands.show', compact('brand'));
    }

    public function edit(Brand $brand): View
    {
        return view('brands.edit', compact('brand'));
    }

    public function update(BrandUpdateRequest $request, Brand $brand): RedirectResponse
    {
        $brand->update($request->validated());

        return redirect()
            ->route('brands.index')
            ->with('success', 'Merek berhasil diperbarui.');
    }

    public function destroy(Brand $brand): RedirectResponse
    {
        $brand->delete();

        return redirect()
            ->route('brands.index')
            ->with('success', 'Merek berhasil dihapus.');
    }

    public function exportExcel(Request $request): BinaryFileResponse
    {
        $filters = $request->only(['search']);

        return $this->exportExcel(BrandExport::class, $filters, 'merek-'.now()->format('Ymd-His').'.xlsx');
    }

    public function exportPdf(Request $request): Response
    {
        $filters = $request->only(['search']);
        $items = (new BrandExport($filters))->query()->withCount('phoneModels')->get();

        return $this->exportPdf(
            'reports.pdf.brand',
            compact('items', 'filters'),
            'merek-'.now()->format('Ymd-His').'.pdf'
        );
    }

    public function print(Request $request): Response
    {
        $filters = $request->only(['search']);
        $items = (new BrandExport($filters))->query()->withCount('phoneModels')->get();

        return $this->printView('reports.pdf.brand', compact('items', 'filters'));
    }
}
