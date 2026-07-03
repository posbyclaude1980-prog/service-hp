<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Cetak' }} — {{ $settings['shop_name'] ?? config('app.name') }}</title>
    <style>
        /* Dot matrix safe: monospace, black on white, no backgrounds/shadows/flex */
        html, body {
            margin: 0;
            padding: 0;
            color: #000;
            background: #fff;
            font-family: 'Courier New', Courier, monospace;
            font-size: 12pt;
            line-height: 1.25;
        }

        .dm-wrap {
            margin: 0 auto;
            padding: 8px;
        }

        .paper-a4 .dm-wrap { width: 190mm; max-width: 100%; }
        .paper-f4 .dm-wrap { width: 195mm; max-width: 100%; }
        .paper-continuous .dm-wrap { width: 80ch; max-width: 100%; }

        .dm-center { text-align: center; }
        .dm-right { text-align: right; }
        .dm-bold { font-weight: bold; }
        .dm-small { font-size: 10pt; }
        .dm-title { font-size: 14pt; font-weight: bold; text-align: center; }
        .dm-subtitle { font-size: 11pt; text-align: center; }
        .dm-line { margin: 6px 0; border: none; border-top: 1px solid #000; height: 0; }
        .dm-line-dashed { border-top-style: dashed; }
        .dm-preline { white-space: pre-wrap; word-wrap: break-word; }

        /* PIN / Pattern grid for receipts */
        .pin-grid { display: inline-block; margin-top: 6px; }
        .pin-row { display: flex; gap: 8px; margin-bottom: 4px; }
        .pin-cell { display: inline-flex; width: 26px; height: 26px; align-items: center; justify-content: center; border: 1px solid #000; border-radius: 50%; font-weight: 700; font-size: 11pt; }
        .pin-checkbox-row { display: flex; gap: 8px; margin-top: 6px; }
        .pin-checkbox { display: inline-block; width: 34px; height: 18px; border: 1px solid #000; }

        .dm-header-row { display: flex; align-items: flex-start; justify-content: flex-start; gap: 12px; margin-bottom: 8px; }
        .dm-header-logo { flex: 0 0 auto; }
        .dm-header-right { flex: 1 1 auto; min-width: 220px; }
        .dm-header-title { text-align: left; margin-bottom: 4px; }
        .dm-header-tagline { text-align: left; margin-top: 0; font-size: 11pt; font-weight: bold; }
        .dm-header-meta { text-align: left; margin-top: 2px; }
        .dm-logo { display: inline-block; max-height: 48px; max-width: 160px; width: auto; margin-bottom: 0; }

        table.dm-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        table.dm-table th,
        table.dm-table td {
            border: 1px solid #000;
            padding: 2px 4px;
            vertical-align: top;
            text-align: left;
        }

        table.dm-table th { font-weight: bold; }

        table.dm-kv { width: 100%; border-collapse: collapse; }
        table.dm-kv td { padding: 1px 0; vertical-align: top; border: none; }
        table.dm-kv td.dm-k { width: 28%; }
        table.dm-kv td.dm-c { width: 2%; }
        table.dm-kv td.dm-v { width: 70%; }

        table.dm-items th, table.dm-items td { font-size: 11pt; }

        .dm-sign { margin-top: 24px; }
        .dm-sign table { width: 100%; border-collapse: collapse; }
        .dm-sign td { width: 50%; text-align: center; vertical-align: top; border: none; padding-top: 32px; }

        /* Toolbar — hanya layar */
        .dm-toolbar {
            position: sticky;
            top: 0;
            z-index: 100;
            background: #f0f0f0;
            border-bottom: 2px solid #000;
            padding: 8px 12px;
            font-family: Arial, sans-serif;
            font-size: 13px;
        }

        .dm-toolbar label { margin-right: 4px; }
        .dm-toolbar select, .dm-toolbar button, .dm-toolbar a.btn-dm {
            font-family: Arial, sans-serif;
            font-size: 13px;
            margin-right: 8px;
            padding: 4px 10px;
        }

        .dm-toolbar a.btn-dm {
            display: inline-block;
            border: 1px solid #333;
            background: #fff;
            color: #000;
            text-decoration: none;
        }

        @media print {
            .dm-toolbar { display: none !important; }
            html, body { background: #fff; }
            .dm-wrap { width: 100%; padding: 0; }
        }

        @page {
            margin: 10mm;
        }

        @media print {
            body.paper-f4 .dm-wrap { width: 195mm; }
            body.paper-continuous .dm-wrap { width: 80ch; max-width: 100%; }
        }
    </style>
    @stack('styles')
</head>
<body class="{{ $paperClass ?? 'paper-a4' }}" @if(!empty($autoPrint)) onload="window.print()" @endif>

@if(!empty($preview))
    @include('prints.partials.toolbar')
@endif

<div class="dm-wrap">
    @yield('content')
</div>

@stack('scripts')
</body>
</html>
