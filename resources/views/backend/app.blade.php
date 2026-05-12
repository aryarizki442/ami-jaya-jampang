<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- CUSTOM CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/color.css') }}">
    <link rel="stylesheet" href="{{ asset('css/login/login.css') }}">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <link rel="stylesheet" href="{{ asset('css/button.css') }}">


    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            display: flex;
            min-height: 100vh;
            background: #F5F6FA;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            width: 260px;
            background: linear-gradient(180deg, #0D3523, #1F7D53);
            color: #fff;
            display: flex;
            flex-direction: column;
            padding: 25px 20px;
            transition: 0.3s;
        }

        .brand {
            display: flex;
            justify-content: center;
            margin-bottom: 40px;
        }

        .brand img {
            width: 160px;
        }

        .menu {
            list-style: none;
            padding: 0;
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
            transition: 0.2s;
        }

        .menu a:hover {
            background: rgba(255, 255, 255, 0.15);
        }

        .menu a.active {
            background: rgba(255, 255, 255, 0.25);
        }

        .menu i {
            font-size: 20px;
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
            padding: 0 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            gap: 15px;
        }

        .hamburger {
            font-size: 24px;
            cursor: pointer;
            display: none;
        }

        .logout {
            font-size: 20px;
            cursor: pointer;
            margin-left: auto;
        }

        /* ===== CONTENT ===== */
        .content {
            flex: 1;
            padding: 25px;
            overflow-y: auto;
        }

        /* ===== OVERLAY ===== */
        .overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.4);
            display: none;
            z-index: 998;
        }

        /* ===== MOBILE ===== */
        @media(max-width:768px) {

            .hamburger {
                display: block;
            }

            .sidebar {
                position: fixed;
                left: -260px;
                top: 0;
                height: 100%;
                z-index: 999;
            }

            .sidebar.active {
                left: 0;
            }

            .overlay.active {
                display: block;
            }
        }

        .modal-header,
        .modal-footer {
            border: none !important;
        }

        .modal-content {
            border: none !important;
            box-shadow: none !important;
        }
    </style>
</head>

<body>

    <!-- OVERLAY -->
    <div class="overlay" id="overlay"></div>

    <!-- SIDEBAR -->
    <div class="sidebar" id="sidebar">
        <div class="brand">
            <img src="{{ asset('images/logo/logo-putih.png') }}">
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
                <a href="{{ route('admin.product.index') }}"
                    class="{{ request()->routeIs('admin.product.index') ? 'active' : '' }}">
                    <i class="ri-box-3-line"></i>
                    <span>Produk</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.order') }}" class="{{ request()->routeIs('admin.order') ? 'active' : '' }}">
                    <i class="ri-list-check"></i>
                    <span>Pesanan</span>
                </a>
            </li>
            <li>
                 <a href="">
                    <i class="ri-wallet-3-line"></i>
                    <span>Pembayaran</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.category') }}"
                    class="{{ request()->routeIs('admin.category') ? 'active' : '' }}">
                    <i class="ri-list-check"></i>
                    <span>Kategori</span>
                </a>
            </li>
            <li>
                <a href="#"><i class="ri-bar-chart-2-line"></i><span>Laporan</span></a>
            </li>

            <li>
                <a href="#"><i class="ri-settings-3-line"></i><span>Pengaturan</span></a>
            </li>
        </ul>
    </div>

    <!-- MAIN -->
    <div class="main">

        <!-- TOPBAR -->
        <div class="topbar">
            <i class="ri-menu-line hamburger" id="menuToggle"></i>

            <h5 class="mb-0 fw-semibold">
                @yield('title')
            </h5>

            <i class="ri-logout-box-r-line logout"></i>
        </div>

        <!-- CONTENT -->
        <div class="content">
            @yield('content')
        </div>

    </div>

    <!-- MODAL LOGOUT -->
    <div class="modal fade" id="logoutModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header justify-content-center">
                    <h5 class="fw-semibold m-0">Logout</h5>
                </div>

                <div class="modal-body text-center">
                    <p class="mb-0">Apakah Anda yakin ingin keluar dari akun ini?</p>
                </div>

                <div class="modal-footer justify-content-center gap-2">
                    <button class="btn btn-delete-second" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-delete-main text-custom-red" id="confirmLogoutBtn">Logout</button>
                </div>

            </div>
        </div>
    </div>

    <script>
        const toggle = document.getElementById('menuToggle');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');

        toggle.addEventListener('click', () => {
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
        });

        overlay.addEventListener('click', () => {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/pagination.js') }}"></script>
    <script src="{{ asset('js/formatText.js') }}"></script>


    <script>
        document.getElementById('confirmLogoutBtn').addEventListener('click', async function() {

            const token = localStorage.getItem('token');

            // 🔥 guard kalau token tidak ada
            if (!token) {
                window.location.href = '/login';
                return;
            }

            try {
                const res = await fetch('/api/logout', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': 'Bearer ' + token
                    }
                });

                // 🔥 kalau unauthorized tetap paksa logout frontend
                if (res.status === 401) {
                    localStorage.removeItem('token');
                    window.location.href = '/login';
                    return;
                }

                const data = await res.json();

                // tutup modal
                const modalEl = document.getElementById('logoutModal');
                const modal = bootstrap.Modal.getInstance(modalEl);
                modal.hide();

                // hapus token
                localStorage.removeItem('token');

                // redirect
                window.location.href = '/login';

            } catch (err) {
                console.error(err);
                alert('Logout gagal');
            }

        });

        document.querySelector('.logout').addEventListener('click', function() {
            const modal = new bootstrap.Modal(document.getElementById('logoutModal'));
            modal.show();
        });
    </script>
</body>

</html>
