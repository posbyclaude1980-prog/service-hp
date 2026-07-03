@extends('layouts.pdf')

@section('title', 'Pengaturan Toko')

@section('content')
<p class="fw-bold mb-1">Pengaturan Toko</p>
<table>
    <thead><tr><th width="180">Kunci</th><th>Nilai</th></tr></thead>
    <tbody>
        @foreach($allSettings ?? $settings ?? [] as $key => $value)
        <tr>
            <td>{{ is_object($value) ? $value->key : $key }}</td>
            <td>{{ is_object($value) ? $value->value : $value }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
