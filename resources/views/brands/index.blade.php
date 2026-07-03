@extends('layouts.app')

@section('title', 'Merek')

@section('content')
@include('partials.page-header', [
    'title' => 'Data Merek',
    'breadcrumbs' => ['Master Data' => '#', 'Merek' => route('brands.index')],
    'actions' => ''
])

<div class="card mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-6">
                <label class="form-label small">Cari</label>
                <input type="text" name="search" class="form-control" placeholder="Nama merek..." value="{{ request('search') }}">
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
                    <th>Nama Merek</th>
                    <th>Jumlah Model</th>
                    <th>Dibuat</th>
                    <th class="text-center" style="width:120px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($brands as $i => $brand)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $brand->name }}</td>
                    <td>{{ $brand->phone_models_count ?? $brand->phoneModels()->count() }}</td>
                    <td>{{ $brand->created_at?->format('d/m/Y') }}</td>
                    <td class="text-center">
                        <a href="{{ route('brands.show', $brand) }}" class="btn btn-sm btn-outline-info"><i class="bi bi-eye"></i></a>
                        <a href="{{ route('brands.edit', $brand) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
                        @include('partials.delete-form', ['action' => route('brands.destroy', $brand), 'name' => $brand->name])
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@include('partials.datatable-init')
