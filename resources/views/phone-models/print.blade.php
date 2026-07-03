@extends('layouts.print')

@section('title', 'Model HP')

@section('content')
<div class="print-meta"><div><strong>Model HP</strong></div><div>{{ now()->format('d/m/Y H:i') }}</div></div>
<table>
    <tr><th width="120">Merek</th><td>{{ $phoneModel->brand->name ?? '-' }}</td></tr>
    <tr><th>Nama Model</th><td>{{ $phoneModel->name }}</td></tr>
</table>
@endsection
