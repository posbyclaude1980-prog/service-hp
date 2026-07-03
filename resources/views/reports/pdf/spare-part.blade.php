@extends('layouts.print')

@section('title', 'Daftar Sparepart')

@section('content')
    <h1>Daftar Sparepart</h1>
    <div class="meta">Dicetak: {{ now()->format('d/m/Y H:i') }}</div>
    <table>
        <thead>
            <tr>
                <th>Kode</th>
                <th>Nama</th>
                <th>Stok</th>
                <th>Stok Min</th>
                <th>Harga Beli</th>
                <th>Harga Jual</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $item)
                <tr>
                    <td>{{ $item->code }}</td>
                    <td>{{ $item->name }}</td>
                    <td class="text-right">{{ $item->stock }}</td>
                    <td class="text-right">{{ $item->min_stock }}</td>
                    <td class="text-right">{{ number_format($item->purchase_price, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($item->selling_price, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center">Tidak ada data.</td></tr>
            @endforelse
        </tbody>
    </table>
@endsection
