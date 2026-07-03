@extends('layouts.print')

@section('title', 'Daftar Supplier')

@section('content')
    <h1>Daftar Supplier</h1>
    <div class="meta">Dicetak: {{ now()->format('d/m/Y H:i') }}</div>
    <table>
        <thead>
            <tr>
                <th>Kode</th>
                <th>Nama</th>
                <th>Kontak</th>
                <th>Telepon</th>
                <th>Alamat</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $item)
                <tr>
                    <td>{{ $item->code }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->contact }}</td>
                    <td>{{ $item->phone }}</td>
                    <td>{{ $item->address }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center">Tidak ada data.</td></tr>
            @endforelse
        </tbody>
    </table>
@endsection
