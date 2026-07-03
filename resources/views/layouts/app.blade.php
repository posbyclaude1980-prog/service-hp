<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — {{ config('app.name', 'Service HP') }}</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/klinik-gadget-logo-small.svg') }}">
    <link rel="shortcut icon" href="{{ asset('images/klinik-gadget-logo-small.svg') }}">

  {{-- Bootstrap 5.3 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

  {{-- DataTables BS5 --}}
    <link href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" rel="stylesheet">

    <style>
        :root {
            --sidebar-width: 260px;
            --sidebar-bg: #1e293b;
            --sidebar-hover: #334155;
            --sidebar-active: #3b82f6;
            --topbar-height: 60px;
        }

        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background-color: #f1f5f9;
            min-height: 100vh;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--sidebar-bg);
            color: #e2e8f0;
            z-index: 1040;
            overflow-y: auto;
            transition: transform .3s ease;
        }

        .sidebar-brand {
            height: var(--topbar-height);
            display: flex;
            align-items: center;
            padding: 0 1.25rem;
            border-bottom: 1px solid rgba(255,255,255,.08);
            font-weight: 700;
            font-size: 1.1rem;
            color: #fff;
            text-decoration: none;
        }

        .sidebar-brand i { color: var(--sidebar-active); margin-right: .5rem; font-size: 1.4rem; }

        .sidebar-nav { padding: .75rem 0; }

        .sidebar-heading {
            padding: .75rem 1.25rem .35rem;
            font-size: .7rem;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: #94a3b8;
            font-weight: 600;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            padding: .6rem 1.25rem;
            color: #cbd5e1;
            text-decoration: none;
            font-size: .9rem;
            border-left: 3px solid transparent;
            transition: all .15s ease;
        }

        .sidebar-link:hover { background: var(--sidebar-hover); color: #fff; }
        .sidebar-link.active {
            background: rgba(59,130,246,.15);
            color: #fff;
            border-left-color: var(--sidebar-active);
        }
        .sidebar-link i { width: 1.5rem; margin-right: .5rem; font-size: 1rem; }

        .sidebar-submenu .sidebar-link { padding-left: 2.75rem; font-size: .85rem; }

        .main-wrapper {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .top-navbar {
            height: var(--topbar-height);
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1.5rem;
            position: sticky;
            top: 0;
            z-index: 1030;
        }

        .main-content { flex: 1; padding: 1.5rem; }

        .page-header { margin-bottom: 1.5rem; }
        .page-header h1 { font-size: 1.5rem; font-weight: 700; color: #1e293b; margin: 0; }
        .page-header .breadcrumb { margin: .25rem 0 0; font-size: .85rem; }

        .stat-card {
            border: none;
            border-radius: .75rem;
            box-shadow: 0 1px 3px rgba(0,0,0,.06);
            transition: transform .15s ease;
        }
        .stat-card:hover { transform: translateY(-2px); }
        .stat-card .stat-icon {
            width: 48px; height: 48px;
            border-radius: .75rem;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.4rem;
        }

        .card { border: none; border-radius: .75rem; box-shadow: 0 1px 3px rgba(0,0,0,.06); }
        .card-header { background: #fff; border-bottom: 1px solid #e2e8f0; font-weight: 600; }

        .table th { font-size: .8rem; text-transform: uppercase; letter-spacing: .03em; color: #64748b; font-weight: 600; }
        .badge-status { font-weight: 500; padding: .4em .75em; }

        .sidebar-toggle { display: none; }

        @media (max-width: 991.98px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .main-wrapper { margin-left: 0; }
            .sidebar-toggle { display: inline-flex; }
            .sidebar-overlay {
                display: none;
                position: fixed; inset: 0;
                background: rgba(0,0,0,.5);
                z-index: 1035;
            }
            .sidebar-overlay.show { display: block; }
        }
    </style>

    @stack('styles')
</head>
<body>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <aside class="sidebar" id="sidebar">
        <a href="{{ route('dashboard') }}" class="sidebar-brand">
            <img src="{{ asset('images/klinik-gadget-logo-small.svg') }}" alt="Klinik Gadget Bandung" style="height:36px; width:auto; margin-right:8px;">
            <span style="font-size:1rem">{{ $shopName ?? 'Klinik Gadget' }}</span>
        </a>

        <nav class="sidebar-nav">
            <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>

            <a href="{{ route('print.payments.cashier-receipt.form') }}" class="sidebar-link {{ request()->routeIs('print.payments.cashier-receipt.form') ? 'active' : '' }}">
                <i class="bi bi-journal-text"></i> Input Kwitansi
            </a>

            <a href="{{ route('service-orders.index') }}" class="sidebar-link {{ request()->routeIs('service-orders.*') ? 'active' : '' }}">
                <i class="bi bi-tools"></i> Service Order
            </a>

            @if(auth()->user()?->isTeknisi() || auth()->user()?->isOwner() || auth()->user()?->isAdmin())
            <a href="{{ route('technician.jobs.index') }}" class="sidebar-link {{ request()->routeIs('technician.jobs.*') ? 'active' : '' }}">
                <i class="bi bi-wrench-adjustable"></i> Pekerjaan Teknisi
            </a>
            @endif

            <a href="{{ route('customers.index') }}" class="sidebar-link {{ request()->routeIs('customers.*') ? 'active' : '' }}">
                <i class="bi bi-people"></i> Pelanggan
            </a>

            <div class="sidebar-heading">Master Data</div>
            <div class="sidebar-submenu">
                <a href="{{ route('brands.index') }}" class="sidebar-link {{ request()->routeIs('brands.*') ? 'active' : '' }}">
                    <i class="bi bi-tag"></i> Merek
                </a>
                <a href="{{ route('phone-models.index') }}" class="sidebar-link {{ request()->routeIs('phone-models.*') ? 'active' : '' }}">
                    <i class="bi bi-phone"></i> Model HP
                </a>
                <a href="{{ route('spare-parts.index') }}" class="sidebar-link {{ request()->routeIs('spare-parts.*') ? 'active' : '' }}">
                    <i class="bi bi-box-seam"></i> Spare Part
                </a>
                <a href="{{ route('suppliers.index') }}" class="sidebar-link {{ request()->routeIs('suppliers.*') ? 'active' : '' }}">
                    <i class="bi bi-truck"></i> Supplier
                </a>
            </div>

            <a href="{{ route('payments.index') }}" class="sidebar-link {{ request()->routeIs('payments.*') ? 'active' : '' }}">
                <i class="bi bi-cash-coin"></i> Pembayaran
            </a>

            <a href="{{ route('purchases.index') }}" class="sidebar-link {{ request()->routeIs('purchases.*') ? 'active' : '' }}">
                <i class="bi bi-cart-plus"></i> Pembelian
            </a>

            <div class="sidebar-heading">Laporan</div>
            <div class="sidebar-submenu">
                <a href="{{ route('reports.service') }}" class="sidebar-link {{ request()->routeIs('reports.service*') ? 'active' : '' }}">
                    <i class="bi bi-clipboard-data"></i> Laporan Service
                </a>
                <a href="{{ route('reports.revenue') }}" class="sidebar-link {{ request()->routeIs('reports.revenue*') ? 'active' : '' }}">
                    <i class="bi bi-graph-up"></i> Laporan Pendapatan
                </a>
                <a href="{{ route('reports.stock') }}" class="sidebar-link {{ request()->routeIs('reports.stock*') ? 'active' : '' }}">
                    <i class="bi bi-boxes"></i> Laporan Stok
                </a>
                <a href="{{ route('print.index') }}" class="sidebar-link {{ request()->routeIs('print.*') ? 'active' : '' }}">
                    <i class="bi bi-printer"></i> Cetak Dot Matrix
                </a>
            </div>

            @auth
                @if(auth()->user()->canManageUsers())
                    <div class="sidebar-heading">Administrasi</div>
                    <a href="{{ route('users.index') }}" class="sidebar-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                        <i class="bi bi-person-gear"></i> Pengguna
                    </a>
                    <a href="{{ route('settings.index') }}" class="sidebar-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                        <i class="bi bi-gear"></i> Pengaturan
                    </a>
                @endif
            @endauth
        </nav>
    </aside>

    <div class="main-wrapper">
        <header class="top-navbar">
            <div class="d-flex align-items-center gap-2">
                <button class="btn btn-light btn-sm sidebar-toggle" id="sidebarToggle" type="button">
                    <i class="bi bi-list"></i>
                </button>
                <img src="{{ asset('images/klinik-gadget-logo-small.svg') }}" alt="Logo" style="height:28px; width:auto;" class="d-none d-md-inline">
                <span class="text-muted d-none d-md-inline">@yield('title', 'Dashboard')</span>
            </div>

            <div class="d-flex align-items-center gap-3">
                <span class="text-muted small d-none d-lg-inline">{{ now()->translatedFormat('l, d F Y') }}</span>

                @auth
                    <div class="dropdown">
                        <button class="btn btn-light dropdown-toggle d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown">
                            <span class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center" style="width:32px;height:32px;font-size:.85rem;">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </span>
                            <span class="d-none d-md-inline">{{ auth()->user()->name }}</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow">
                            <li><h6 class="dropdown-header">{{ auth()->user()->email }}</h6></li>
                            <li><span class="dropdown-item-text small text-muted">Role: {{ auth()->user()->roleLabel() }}</span></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="bi bi-box-arrow-right me-2"></i> Keluar
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @endauth
            </div>
        </header>

        <main class="main-content">
            @if(session('success') || session('error') || session('warning') || session('info'))
                <div class="mb-3">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    @if(session('warning'))
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('warning') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    @if(session('info'))
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            <i class="bi bi-info-circle me-2"></i>{{ session('info') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                </div>
            @endif

            @yield('content')
        </main>
    </div>

  {{-- jQuery --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  {{-- Bootstrap 5.3 --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  {{-- DataTables --}}
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
  {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

    <script>
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

        $('#sidebarToggle, #sidebarOverlay').on('click', function () {
            $('#sidebar').toggleClass('show');
            $('#sidebarOverlay').toggleClass('show');
        });

        function formatRupiah(amount) {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(amount);
        }
    </script>

    @include('partials.alerts')
    @stack('scripts')
</body>
</html>
