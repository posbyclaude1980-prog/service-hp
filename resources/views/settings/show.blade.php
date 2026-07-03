@extends('layouts.app')

@section('title', 'Semua Pengaturan')

@section('content')
@include('partials.page-header', [
    'title' => 'Semua Pengaturan',
    'breadcrumbs' => ['Pengaturan' => route('settings.index'), 'Semua' => '#'],
    'actions' => '
        <a href="'.route('settings.create').'" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i> Tambah</a>
        <a href="'.route('settings.print').'" class="btn btn-outline-secondary btn-sm" target="_blank"><i class="bi bi-printer"></i> Cetak</a>
        <a href="'.route('settings.pdf').'" class="btn btn-outline-danger btn-sm"><i class="bi bi-file-pdf"></i> PDF</a>
    '
])

<div class="card">
    <div class="card-body">
        <table class="table table-hover datatable w-100">
            <thead>
                <tr>
                    <th>Kunci</th>
                    <th>Nilai</th>
                    <th>Diperbarui</th>
                    <th class="text-center" style="width:100px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($allSettings ?? [] as $setting)
                <tr>
                    <td><code>{{ $setting->key }}</code></td>
                    <td>{{ Str::limit($setting->value, 80) }}</td>
                    <td>{{ $setting->updated_at?->format('d/m/Y H:i') }}</td>
                    <td class="text-center">
                        <a href="{{ route('settings.edit', $setting) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@include('partials.datatable-init')
