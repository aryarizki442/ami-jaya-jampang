<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Icons (cukup 1 saja) -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            display: flex;
            height: 100vh;
            overflow: hidden;
            background: #f4f6f9;
        }

        /* Page Title */
        .search-width {
            width: 40%;
            min-width: 250px;
            max-width: 500px;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            width: 260px;
            background: linear-gradient(180deg, #0D3523, #1F7D53);
            color: #fff;
            display: flex;
            flex-direction: column;
            padding: 25px 20px;
        }

        .brand {
            display: flex;
            justify-content: center;
            /* center horizontal */
            align-items: center;
            margin-bottom: 40px;
        }

        .menu {
            list-style: none;
            padding-left: 0;
        }

        .menu li {
            margin-bottom: 10px;
        }

        .menu a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 14px;
            border-radius: 10px;
            color: #fff;
            text-decoration: none;
            transition: .3s;
            font-size: 14px;
        }

        .menu a i {
            font-size: 22px;
        }

        .menu a:hover {
            background: rgba(255, 255, 255, 0.15);
        }

        .menu a.active {
            background: rgba(255, 255, 255, 0.25);
        }

        /* ===== MAIN ===== */
        .main {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        /* ===== TOPBAR ===== */
        .topbar {
            height: 60px;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 25px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .logout {
            font-size: 20px;
            cursor: pointer;
        }

        /* ===== CONTENT ===== */
        .content {
            flex: 1;
            padding: 25px;
            overflow-y: auto;
        }

        /* Responsive */
        @media(max-width:768px) {
            .sidebar {
                width: 70px;
            }

            .sidebar .brand h2,
            .menu a span {
                display: none;
            }
        }
    </style>
</head>

<body>

    <!-- SIDEBAR -->
    <div class="sidebar">
        <div class="brand">
            <a class="sidebar-brand fw-bold " href="#">
                <img src="{{ asset('images/logo/logo-putih.png') }}" width="180">
            </a>
        </div>

        <ul class="menu">
            <li>
                <a href="{{ route('admin.dashboard') }}"
                    class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="ri-dashboard-line"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.product') }}"
                    class="{{ request()->routeIs('admin.product') ? 'active' : '' }}">
                    <i class="ri-box-3-line"></i>
                    <span>Produk</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="ri-shopping-cart-2-line"></i>
                    <span>Pesanan</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="ri-wallet-3-line"></i>
                    <span>Pembayaran</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="ri-bar-chart-2-line"></i>
                    <span>Laporan</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="ri-file-list-3-line"></i>
                    <span>Audit Log</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="ri-settings-3-line"></i>
                    <span>Pengaturan</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- MAIN -->
    <div class="main">

        <!-- TOPBAR -->
        <div class="topbar position-relative">

            @hasSection('page-title')
                <h5 class="mb-0 fw-semibold">
                    @yield('page-title')
                </h5>
            @else
                <div class="position-relative search-width">
                    <input type="text" class="form-control ps-4 pe-5" placeholder="Cari disini">
                    <i class="ri-search-line position-absolute top-50 end-0 translate-middle-y me-3 text-muted"></i>
                </div>
            @endif

            <i class="ri-logout-box-r-line logout ms-auto"></i>

        </div>

        <!-- CONTENT -->
        <div class="content">
            @yield('content')
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.iconify.design/3/3.1.0/iconify.min.js"></script>
</body>

</html>
