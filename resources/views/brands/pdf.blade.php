@extends('layouts.pdf')

@section('title', 'Merek - '.$brand->name)

@section('content')
<p class="fw-bold mb-1">Merek: {{ $brand->name }}</p>
<table>
    <thead><tr><th>No</th><th>Nama Model</th><th>Dibuat</th></tr></thead>
    <tbody>
        @foreach($brand->phoneModels ?? [] as $i => $model)
        <tr><td>{{ $i + 1 }}</td><td>{{ $model->name }}</td><td>{{ $model->created_at?->format('d/m/Y') }}</td></tr>
        @endforeach
    </tbody>
</table>
@endsection
