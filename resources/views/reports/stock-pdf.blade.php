@extends('layouts.pdf')

@section('title', 'Laporan Stok')

@section('content')
<div class="pdf-meta">
    <strong>LAPORAN STOK SPARE PART</strong><br>
    Dicetak: {{ now()->format('d/m/Y H:i') }} |
    Total Item: {{ $summary['total_items'] ?? 0 }} |
    Nilai Stok: Rp {{ number_format($summary['stock_value'] ?? 0, 0, ',', '.') }}
</div>

<table>
    <thead>
        <tr>
            <th>Kode</th>
            <th>Nama</th>
            <th class="text-center">Stok</th>
            <th class="text-center">Min</th>
            <th class="text-end">Harga Beli</th>
            <th class="text-end">Nilai Stok</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($spareParts ?? [] as $part)
        <tr>
            <td>{{ $part->code }}</td>
            <td>{{ $part->name }}</td>
            <td class="text-center">{{ $part->stock }}</td>
            <td class="text-center">{{ $part->min_stock }}</td>
            <td class="text-end">Rp {{ number_format($part->purchase_price, 0, ',', '.') }}</td>
            <td class="text-end">Rp {{ number_format($part->stock * $part->purchase_price, 0, ',', '.') }}</td>
            <td>
                @if($part->stock <= 0) Habis
                @elseif($part->isLowStock()) Menipis
                @else Tersedia @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
