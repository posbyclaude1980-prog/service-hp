@extends('layouts.print')

@section('title', 'Laporan Servis')

@section('content')
    <h1>Laporan Servis</h1>
    <div class="meta">Dicetak: {{ now()->format('d/m/Y H:i') }}</div>
    <table>
        <thead>
            <tr>
                <th>No. Tiket</th>
                <th>Pelanggan</th>
                <th>Status</th>
                <th>Teknisi</th>
                <th>Total</th>
                <th>Tgl Masuk</th>
                <th>Tgl Selesai</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $item)
                <tr>
                    <td>{{ $item->ticket_number }}</td>
                    <td>{{ $item->customer?->name }}</td>
                    <td>{{ $item->status?->label() }}</td>
                    <td>{{ $item->technician?->name }}</td>
                    <td class="text-right">{{ number_format($item->total_cost, 0, ',', '.') }}</td>
                    <td>{{ $item->entry_date?->format('d/m/Y') }}</td>
                    <td>{{ $item->completion_date?->format('d/m/Y') ?? '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center">Tidak ada data.</td></tr>
            @endforelse
        </tbody>
    </table>
@endsection
