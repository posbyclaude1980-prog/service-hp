@extends('layouts.app')

@section('title', 'Model HP')

@section('content')
@include('partials.page-header', [
    'title' => 'Data Model HP',
    'breadcrumbs' => ['Master Data' => '#', 'Model HP' => route('phone-models.index')],
    'actions' => ''
])

<div class="card mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label small">Cari</label>
                <input type="text" name="search" class="form-control" placeholder="Nama model..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small">Merek</label>
                <select name="brand_id" class="form-select">
                    <option value="">Semua Merek</option>
                    @foreach($brands ?? [] as $brand)
                        <option value="{{ $brand->id }}" @selected(request('brand_id') == $brand->id)>{{ $brand->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search"></i> Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-hover datatable w-100">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Merek</th>
                    <th>Nama Model</th>
                    <th>Dibuat</th>
                    <th class="text-center" style="width:120px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($phoneModels as $i => $model)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $model->brand->name ?? '-' }}</td>
                    <td>{{ $model->name }}</td>
                    <td>{{ $model->created_at?->format('d/m/Y') }}</td>
                    <td class="text-center">
                        <a href="{{ route('phone-models.show', $model) }}" class="btn btn-sm btn-outline-info"><i class="bi bi-eye"></i></a>
                        <a href="{{ route('phone-models.edit', $model) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
                        @include('partials.delete-form', ['action' => route('phone-models.destroy', $model), 'name' => $model->name])
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@include('partials.datatable-init')
