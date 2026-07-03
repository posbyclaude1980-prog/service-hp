@extends('layouts.print')

@section('title', 'Laporan Pendapatan')

@section('content')
    <h1>Laporan Pendapatan</h1>
    <div class="meta">Dicetak: {{ now()->format('d/m/Y H:i') }}</div>
    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>No. Tiket</th>
                <th>Pelanggan</th>
                <th>Jumlah</th>
                <th>Metode</th>
                <th>Petugas</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $item)
                <tr>
                    <td>{{ $item->payment_date?->format('d/m/Y') }}</td>
                    <td>{{ $item->serviceOrder?->ticket_number }}</td>
                    <td>{{ $item->serviceOrder?->customer?->name }}</td>
                    <td class="text-right">{{ number_format($item->amount, 0, ',', '.') }}</td>
                    <td>{{ $item->method?->label() }}</td>
                    <td>{{ $item->user?->name }}</td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center">Tidak ada data.</td></tr>
            @endforelse
        </tbody>
    </table>
@endsection
