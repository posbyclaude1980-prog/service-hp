@extends('layouts.app')

@section('title', 'Detail Merek')

@section('content')
@include('partials.page-header', [
    'title' => $brand->name,
    'breadcrumbs' => ['Merek' => route('brands.index'), $brand->name => '#'],
    'actions' => '
        <a href="'.route('brands.edit', $brand).'" class="btn btn-warning"><i class="bi bi-pencil"></i> Edit</a>
    '
])

<div class="card">
    <div class="card-header">Model HP — {{ $brand->name }}</div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead><tr><th>No</th><th>Nama Model</th><th>Dibuat</th></tr></thead>
            <tbody>
                @forelse($brand->phoneModels ?? [] as $i => $model)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td><a href="{{ route('phone-models.show', $model) }}">{{ $model->name }}</a></td>
                    <td>{{ $model->created_at?->format('d/m/Y') }}</td>
                </tr>
                @empty
                <tr><td colspan="3" class="text-center text-muted py-4">Belum ada model</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
