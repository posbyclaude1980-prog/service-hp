@extends('layouts.print')

@section('title', 'Daftar Pembelian')

@section('content')
    <h1>Daftar Pembelian</h1>
    <div class="meta">Dicetak: {{ now()->format('d/m/Y H:i') }}</div>
    <table>
        <thead>
            <tr>
                <th>No. Invoice</th>
                <th>Supplier</th>
                <th>Tanggal</th>
                <th>Total</th>
                <th>Petugas</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $item)
                <tr>
                    <td>{{ $item->invoice_number }}</td>
                    <td>{{ $item->supplier?->name }}</td>
                    <td>{{ $item->purchase_date?->format('d/m/Y') }}</td>
                    <td class="text-right">{{ number_format($item->total, 0, ',', '.') }}</td>
                    <td>{{ $item->user?->name }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center">Tidak ada data.</td></tr>
            @endforelse
        </tbody>
    </table>
@endsection
