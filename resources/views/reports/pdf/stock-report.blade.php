@extends('layouts.print')

@section('title', 'Laporan Stok')

@section('content')
    <h1>Laporan Stok Sparepart</h1>
    <div class="meta">Dicetak: {{ now()->format('d/m/Y H:i') }}</div>
    <table>
        <thead>
            <tr>
                <th>Kode</th>
                <th>Nama</th>
                <th>Stok</th>
                <th>Stok Min</th>
                <th>Status</th>
                <th>Harga Jual</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $item)
                @php
                    $status = $item->stock <= 0 ? 'Habis' : ($item->isLowStock() ? 'Stok Rendah' : 'Normal');
                @endphp
                <tr>
                    <td>{{ $item->code }}</td>
                    <td>{{ $item->name }}</td>
                    <td class="text-right">{{ $item->stock }}</td>
                    <td class="text-right">{{ $item->min_stock }}</td>
                    <td>{{ $status }}</td>
                    <td class="text-right">{{ number_format($item->selling_price, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center">Tidak ada data.</td></tr>
            @endforelse
        </tbody>
    </table>
@endsection
