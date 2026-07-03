<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>@yield('title', 'Laporan')</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #111; margin: 20px; }
        h1 { font-size: 16px; margin: 0 0 4px; }
        .meta { color: #555; margin-bottom: 16px; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 6px 8px; text-align: left; vertical-align: top; }
        th { background: #f3f4f6; font-weight: bold; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        @media print {
            body { margin: 0; }
            @page { margin: 12mm; }
        }
    </style>
    @stack('styles')
</head>
<body @if(!empty($isPrint)) onload="window.print()" @endif>
    @yield('content')
</body>
</html>
