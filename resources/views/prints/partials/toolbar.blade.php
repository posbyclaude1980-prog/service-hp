@php
    $currentPaper = $paper->value ?? 'a4';
    $baseQuery = request()->except(['paper', 'preview', 'print']);
    $buildUrl = function (array $extra = []) use ($baseQuery) {
        return request()->url().'?'.http_build_query(array_merge($baseQuery, $extra));
    };
@endphp
<div class="dm-toolbar">
    <strong>Dot Matrix Print</strong> — Epson LX-300 &nbsp;|&nbsp;
    <label for="paperSelect">Kertas:</label>
    <select id="paperSelect" onchange="location.href=this.value">
        <option value="{{ $buildUrl(['paper' => 'a4', 'preview' => 1]) }}" @selected($currentPaper === 'a4')>A4</option>
        <option value="{{ $buildUrl(['paper' => 'f4', 'preview' => 1]) }}" @selected($currentPaper === 'f4')>F4 (Folio)</option>
        <option value="{{ $buildUrl(['paper' => 'continuous', 'preview' => 1]) }}" @selected($currentPaper === 'continuous')>Continuous Form</option>
    </select>
    <button type="button" onclick="window.print()">Cetak</button>
    <a class="btn-dm" href="{{ $buildUrl(['paper' => $currentPaper, 'print' => 1]) }}" target="_blank">Buka &amp; Cetak Langsung</a>
    <a class="btn-dm" href="javascript:history.back()">Kembali</a>
</div>
