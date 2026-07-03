@extends('layouts.print')

@section('title', 'Daftar Merek')

@section('content')
    <h1>Daftar Merek HP</h1>
    <div class="meta">Dicetak: {{ now()->format('d/m/Y H:i') }}</div>
    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>Jumlah Model</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $item)
                <tr>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->phone_models_count ?? 0 }}</td>
                </tr>
            @empty
                <tr><td colspan="2" class="text-center">Tidak ada data.</td></tr>
            @endforelse
        </tbody>
    </table>
@endsection
