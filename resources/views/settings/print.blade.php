@extends('layouts.print')

@section('title', 'Pengaturan Toko')

@section('content')
<div class="print-meta"><div><strong>Pengaturan Toko</strong></div><div>{{ now()->format('d/m/Y H:i') }}</div></div>
<table>
    <thead><tr><th>Kunci</th><th>Nilai</th></tr></thead>
    <tbody>
        @foreach($allSettings ?? $settings ?? [] as $key => $value)
        <tr>
            <td><strong>{{ is_object($value) ? $value->key : $key }}</strong></td>
            <td>{{ is_object($value) ? $value->value : $value }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
