<?php

namespace App\Http\Controllers;

use App\Exports\PhoneModelExport;
use App\Http\Controllers\Concerns\ExportsResource;
use App\Http\Requests\PhoneModelStoreRequest;
use App\Http\Requests\PhoneModelUpdateRequest;
use App\Models\Brand;
use App\Models\PhoneModel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;

class PhoneModelController extends Controller
{
    use ExportsResource;

    public function index(Request $request): View
    {
        $filters = $request->only(['search', 'brand_id']);

        $items = PhoneModel::query()
            ->with('brand')
            ->when(
                $filters['brand_id'] ?? null,
                fn ($q, $brandId) => $q->where('brand_id', $brandId)
            )
            ->when(
                $filters['search'] ?? null,
                fn ($q, string $search) => $q->where('name', 'like', "%{$search}%")
            )
            ->latest()
            ->paginate(15)
            ->appends($filters);

        $brands = Brand::query()->orderBy('name')->get();

        return view('phone-models.index', ['phoneModels' => $items, 'filters' => $filters, 'brands' => $brands]);
    }

    public function create(): View
    {
        $brands = Brand::query()->orderBy('name')->get();

        return view('phone-models.create', compact('brands'));
    }

    public function store(PhoneModelStoreRequest $request): RedirectResponse
    {
        PhoneModel::query()->create($request->validated());

        return redirect()
            ->route('phone-models.index')
            ->with('success', 'Model HP berhasil ditambahkan.');
    }

    public function show(PhoneModel $phoneModel): View
    {
        $phoneModel->load('brand');

        return view('phone-models.show', compact('phoneModel'));
    }

    public function edit(PhoneModel $phoneModel): View
    {
        $brands = Brand::query()->orderBy('name')->get();

        return view('phone-models.edit', compact('phoneModel', 'brands'));
    }

    public function update(PhoneModelUpdateRequest $request, PhoneModel $phoneModel): RedirectResponse
    {
        $phoneModel->update($request->validated());

        return redirect()
            ->route('phone-models.index')
            ->with('success', 'Model HP berhasil diperbarui.');
    }

    public function destroy(PhoneModel $phoneModel): RedirectResponse
    {
        $phoneModel->delete();

        return redirect()
            ->route('phone-models.index')
            ->with('success', 'Model HP berhasil dihapus.');
    }

    public function exportExcel(Request $request): BinaryFileResponse
    {
        $filters = $request->only(['search', 'brand_id']);

        return $this->exportExcel(PhoneModelExport::class, $filters, 'model-hp-'.now()->format('Ymd-His').'.xlsx');
    }

    public function exportPdf(Request $request): Response
    {
        $filters = $request->only(['search', 'brand_id']);
        $items = (new PhoneModelExport($filters))->query()->get();

        return $this->exportPdf(
            'reports.pdf.phone-model',
            compact('items', 'filters'),
            'model-hp-'.now()->format('Ymd-His').'.pdf'
        );
    }

    public function print(Request $request): Response
    {
        $filters = $request->only(['search', 'brand_id']);
        $items = (new PhoneModelExport($filters))->query()->get();

        return $this->printView('reports.pdf.phone-model', compact('items', 'filters'));
    }
}
