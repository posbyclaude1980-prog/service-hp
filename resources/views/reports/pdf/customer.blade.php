@extends('layouts.print')

@section('title', 'Daftar Pelanggan')

@section('content')
    <h1>Daftar Pelanggan</h1>
    <div class="meta">Dicetak: {{ now()->format('d/m/Y H:i') }}</div>
    <table>
        <thead>
            <tr>
                <th>Kode</th>
                <th>Nama</th>
                <th>Telepon</th>
                <th>Alamat</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $item)
                <tr>
                    <td>{{ $item->code }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->phone }}</td>
                    <td>{{ $item->address }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center">Tidak ada data.</td></tr>
            @endforelse
        </tbody>
    </table>
@endsection
