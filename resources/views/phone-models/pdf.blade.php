@extends('layouts.pdf')

@section('title', 'Model HP')

@section('content')
<table>
    <tr><th width="100">Merek</th><td>{{ $phoneModel->brand->name ?? '-' }}</td></tr>
    <tr><th>Model</th><td>{{ $phoneModel->name }}</td></tr>
</table>
@endsection
