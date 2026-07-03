@extends('layouts.dotmatrix')

@section('content')
@php
    $serviceOrder = $payment->serviceOrder;
    $customerName = $serviceOrder->customer?->name ?: '.........................';
    $customerPhone = $serviceOrder->contact_phone ?: $serviceOrder->customer?->phone ?: '.........................';
    $serviceDescription = $serviceOrder->complaint ?: '........................................................................';
    $deviceBrand = $serviceOrder->device_brand ?: '........................................................................';
    $deviceModel = $serviceOrder->device_model ?: '........................................................................';
    $amount = number_format($payment->amount, 0, ',', '.');
    $paymentDate = $payment->payment_date?->format('d/m/Y') ?: now()->format('d/m/Y');
    $isWarranty = (int) ($serviceOrder->warranty_days ?? 0) > 0;
@endphp

<div class="dm-center dm-bold">KLINIK GADGET BANDUNG</div>
<div class="dm-center dm-bold">KLINIK GADGET BANDUNG CABANG BANDUNG</div>
<div class="dm-center">SERVICE HANDPHONE TERMURAH DAN BERGARANSI</div>
<div class="dm-center dm-small">Jl. Rereng Wulung No.25, Sukaluyu, Kec. Cibeunying Kaler, Kota Bandung.</div>
<div class="dm-center dm-small">Whatsapp : 085355667769</div>

<hr class="dm-line">

<table class="dm-kv">
    <tr><td class="dm-k">Nota Service</td><td class="dm-c">:</td><td class="dm-v">{{ $serviceOrder->ticket_number }}</td></tr>
    <tr><td class="dm-k">No. Nota</td><td class="dm-c">:</td><td class="dm-v">{{ $payment->id }}</td></tr>
</table>

<div class="dm-bold" style="margin-top:6px">Keterangan Service</div>
<div class="dm-preline" style="border:1px solid #000; padding:4px; min-height:2.5rem;">{{ $serviceDescription }}</div>

<div class="dm-bold" style="margin-top:6px">Merk Handphone</div>
<div class="dm-preline" style="border:1px solid #000; padding:4px; min-height:2.5rem;">{{ $deviceBrand }}</div>

<div class="dm-bold" style="margin-top:6px">Type Handphone</div>
<div class="dm-preline" style="border:1px solid #000; padding:4px; min-height:2.5rem;">{{ $deviceModel }}</div>

<table class="dm-kv" style="margin-top:8px">
    <tr>
        <td class="dm-k">Tanggal</td><td class="dm-c">:</td><td class="dm-v">{{ $paymentDate }}</td>
        <td class="dm-k">Biaya Service</td><td class="dm-c">:</td><td class="dm-v">Rp {{ $amount }}</td>
    </tr>
    <tr>
        <td class="dm-k">Nama</td><td class="dm-c">:</td><td class="dm-v">{{ $customerName }}</td>
        <td class="dm-k">Nomor HP</td><td class="dm-c">:</td><td class="dm-v">{{ $customerPhone }}</td>
    </tr>
</table>

<div class="dm-bold" style="margin-top:8px">Garansi</div>
<table class="dm-kv">
    <tr>
        <td class="dm-v">[ {{ $isWarranty ? 'x' : ' ' }} ] Garansi</td>
        <td class="dm-v">[ {{ $isWarranty ? ' ' : 'x' }} ] Tidak Garansi</td>
    </tr>
</table>

<hr class="dm-line dm-line-dashed">

<div class="dm-bold">Ketentuan service Garansi :</div>
<div class="dm-preline dm-small">Garansi service berlaku dikerusakan yang sama, berlaku selama jangka waktu yang telah ditentukan, untuk klaim garansi, pelanggan wajib menyertakan nota service.</div>

<div class="dm-bold" style="margin-top:6px">Pernyataan Pelanggan (Service Tidak Garansi) :</div>
<div class="dm-preline dm-small">Saya, ......................... memahami bahwa perbaikan handphone saya tidak termasuk garansi, dan saya bertanggung jawab atas biaya perbaikan jika terjadi kerusakan lagi. saya telah memahami dan menyetujui ketentuan ini.</div>

<div class="dm-sign" style="margin-top:12px">
    <table>
        <tr>
            <td>Tanda tangan</td>
        </tr>
        <tr>
            <td style="padding-top:28px">( ......................... )</td>
        </tr>
    </table>
</div>

<hr class="dm-line dm-line-dashed">

<div class="dm-small">
    Syarat dan Ketentuan : Nota ini berlaku selama 1 Bulan. Pengambilan barang servisan harus disertai nota. Jika tidak diambil dalam waktu 1 bulan, maka barang servisan diluar tanggung jawab kami. Kami tidak bertanggung jawab apabila dikemudian hari barang servisan terkait masalah hukum. Menerima nota ini berarti menerima syarat dan ketentuan yang berlaku.
</div>

<div class="dm-bold" style="margin-top:8px">PIN / POLA</div>
<div class="pin-grid" style="margin-top:6px">
    <div class="pin-row">
        <div class="pin-cell">1</div>
        <div class="pin-cell">2</div>
        <div class="pin-cell">3</div>
    </div>
    <div class="pin-row">
        <div class="pin-cell">4</div>
        <div class="pin-cell">5</div>
        <div class="pin-cell">6</div>
    </div>
    <div class="pin-row">
        <div class="pin-cell">7</div>
        <div class="pin-cell">8</div>
        <div class="pin-cell">9</div>
    </div>
    <div class="pin-checkbox-row">
        <div class="pin-checkbox"></div>
        <div class="pin-checkbox"></div>
        <div class="pin-checkbox"></div>
        <div class="pin-checkbox"></div>
        <div class="pin-checkbox"></div>
        <div class="pin-checkbox"></div>
    </div>
</div>

<table class="dm-kv" style="margin-top:8px">
    <tr>
        <td class="dm-v">Konsumen</td>
        <td class="dm-v" style="text-align:right">Counter</td>
    </tr>
    <tr>
        <td class="dm-preline" style="border:1px solid #000; min-height:2.5rem;"></td>
        <td class="dm-preline" style="border:1px solid #000; min-height:2.5rem;"></td>
    </tr>
</table>

<div class="dm-center dm-small" style="margin-top:10px">TERIMA KASIH ATAS KEPERCAYAAN ANDA, KEPUASAN KONSUMEN ADALAH PRIORITAS KAMI</div>
<div class="dm-center dm-small">POWERED BY : KLINIK GADGET BANDUNG</div>
@endsection
