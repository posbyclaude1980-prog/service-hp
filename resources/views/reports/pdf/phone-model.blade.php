@extends('layouts.print')

@section('title', 'Daftar Model HP')

@section('content')
    <h1>Daftar Model HP</h1>
    <div class="meta">Dicetak: {{ now()->format('d/m/Y H:i') }}</div>
    <table>
        <thead>
            <tr>
                <th>Merek</th>
                <th>Model</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $item)
                <tr>
                    <td>{{ $item->brand?->name }}</td>
                    <td>{{ $item->name }}</td>
                </tr>
            @empty
                <tr><td colspan="2" class="text-center">Tidak ada data.</td></tr>
            @endforelse
        </tbody>
    </table>
@endsection
