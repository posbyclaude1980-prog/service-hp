<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'PDF') — {{ config('app.name', 'Service HP') }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1e293b; }
        .pdf-header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #1e293b; padding-bottom: 10px; }
        .pdf-header h1 { font-size: 16px; margin-bottom: 3px; }
        .pdf-header p { font-size: 10px; color: #64748b; }
        .pdf-meta { margin-bottom: 12px; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
        table th, table td { border: 1px solid #94a3b8; padding: 5px 6px; text-align: left; font-size: 10px; }
        table th { background: #e2e8f0; font-weight: bold; }
        .text-end { text-align: right; }
        .text-center { text-align: center; }
        .fw-bold { font-weight: bold; }
        .mt-2 { margin-top: 10px; }
        .mb-1 { margin-bottom: 5px; }
        .summary-table td { border: none; padding: 3px 6px; }
        .summary-table tr td:last-child { text-align: right; }
        page { margin: 20mm 15mm; }
    </style>
    @stack('styles')
</head>
<body>
    <div class="pdf-header">
        <h1>{{ $shopName ?? config('app.name', 'Service HP') }}</h1>
        @if(!empty($shopAddress))<p>{{ $shopAddress }}</p>@endif
        @if(!empty($shopPhone))<p>Telp: {{ $shopPhone }}</p>@endif
    </div>

    @yield('content')
</body>
</html>
