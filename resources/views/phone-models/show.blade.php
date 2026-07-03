@extends('layouts.app')

@section('title', 'Detail Model HP')

@section('content')
@include('partials.page-header', [
    'title' => $phoneModel->name,
    'breadcrumbs' => ['Model HP' => route('phone-models.index'), $phoneModel->name => '#'],
    'actions' => '
        <a href="'.route('phone-models.edit', $phoneModel).'" class="btn btn-warning"><i class="bi bi-pencil"></i> Edit</a>
    '
])

<div class="card mb-3">
    <div class="card-body">
        <table class="table table-borderless table-sm mb-0">
            <tr><td class="text-muted" width="120">Merek</td><td class="fw-semibold">{{ $phoneModel->brand->name ?? '-' }}</td></tr>
            <tr><td class="text-muted">Nama Model</td><td class="fw-semibold">{{ $phoneModel->name }}</td></tr>
            <tr><td class="text-muted">Dibuat</td><td>{{ $phoneModel->created_at?->format('d/m/Y H:i') }}</td></tr>
        </table>
    </div>
</div>
@endsection
