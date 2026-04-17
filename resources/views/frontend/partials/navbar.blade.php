<style>
    /* Navbar Background */
    .navbar-bg {
        background: linear-gradient(90deg, #0D3523, #1F7D53);

    }

    .btn-search {
        background-color: var(--button-green);
    }

    .btn-search:hover {
        background-color: var(--button-green-hover);
    }

    /* MASUK – outline putih */
    .btn-masuk {
        color: #fff;
        background-color: transparent;
        border: 1px solid #fff;
    }

    .btn-masuk:hover {
        background-color: #fff;
        color: #1F7D53;
    }


    .notif-icon {
        font-size: 20px;
        /* icon saja */
        line-height: 1;
    }

    .notif-icon {
        font-size: 20px;
    }

    .cart-icon {
        font-size: 22px;
    }

    /* Area action */
    .action-group {
        gap: 12px;
    }

    /* Ruang khusus cart */
    .cart-wrap {
        padding-right: 16px;
    }

    /* Garis pemisah */
    .action-divider {
        width: 1px;
        height: 28px;
        background-color: rgba(255, 255, 255, 0.6);
    }

    /* CART BADGE */
    .cart-badge {
        position: absolute;
        top: -6px;
        right: -6px;
        background: red;
        color: #fff;
        font-size: 10px;
        padding: 2px 6px;
        border-radius: 50%;
    }

    /* ACTION DIVIDER */
    .action-divider {
        width: 1px;
        height: 28px;
        background: rgba(255, 255, 255, 0.5);
    }

    /* USER DROPDOWN CONTAINER */
    .user-dropdown {
        position: relative;
        display: none;
        /* hide sebelum login */
        cursor: pointer;
    }

    /* TRIGGER (avatar + username) */
    .user-trigger {
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        color: #fff;
        /* sesuaikan warna teks di navbar */
    }

    /* USER AVATAR */
    .user-avatar {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        margin-right: 8px;
        border: 2px solid #fff;
        /* optional jika navbar gelap */
    }

    /* DROPDOWN MENU */
    .dropdown-menu-custom {
        display: none;
        /* default hidden */
        position: absolute;
        top: 42px;
        /* tinggi trigger */
        right: 0;
        background: #fff;
        border-radius: 6px;
        min-width: 160px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        flex-direction: column;
        padding: 8px 0;
        z-index: 999;
    }

    /* ARROW DI ATAS DROPDOWN */
    .dropdown-arrow {
        position: absolute;
        top: -8px;
        right: 18px;
        border-left: 8px solid transparent;
        border-right: 8px solid transparent;
        border-bottom: 8px solid #fff;
    }

    /* SHOW DROPDOWN KETIKA OPEN */
    .user-dropdown.open .dropdown-menu-custom {
        display: block;
    }

    /* LINK DROPDOWN */
    .dropdown-menu-custom a {
        display: block;
        padding: 8px 14px;
        font-size: 14px;
        color: #333;
        text-decoration: none;
    }

    .dropdown-menu-custom a:hover {
        background: #f3f3f3;
    }

    /* LOGOUT LINK */
    .dropdown-menu-custom .logout {
        color: #e74c3c;
        font-weight: 600;
    }
</style>

<header class="navbar-bg">

    <!-- TOP BAR -->
    <div class="border-bottom border-light">
        <div class="container-fluid px-5 py-1 d-flex justify-content-between align-items-center pt-2 pb-2">
            <span class="text-white small">
                Selamat Datang Di Toko Online Ami Jaya Jampang
            </span>

            <div class="d-flex gap-3">
                <a href="#"
                    class="text-white text-decoration-none small d-flex align-items-center gap-2 position-relative">
                    <span class="iconify notif-icon" data-icon="mingcute:notification-line"></span>
                    Notifikasi
                </a>

                <a href="#" class="text-white text-decoration-none small">
                    Bantuan
                </a>
            </div>
        </div>
    </div>

    <!-- BOTTOM BAR -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid px-5">

            <!-- LOGO -->
            <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="/">
                <img src="{{ asset('images/logo/logo-putih.png') }}" width="150">
            </a>

            <!-- TOGGLE -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNavbar">

                <!-- SEARCH -->
                <form class="mx-lg-auto my-3 my-lg-0 flex-grow-1 px-lg-5">
                    <div class="input-group" style="position: relative;">
                        <input type="text" class="form-control" placeholder="Cari Beras Anda disini"
                            style="border-radius: 10px; padding-right: 50px;">
                        <!-- beri padding supaya button tidak menutupi -->

                        <!-- Button dengan icon search -->
                        <button type="button" class="btn-search"
                            style="
                position: absolute;
                right: 5px;
                top: 50%;
                transform: translateY(-50%);
                border-radius: 5px;
                color: white;
                border: none;
                padding: 10px 20px;
                cursor: pointer;
                display: flex;
                align-items: center;
                justify-content: center;">
                            <!-- Icon Search SVG -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="white"
                                viewBox="0 0 16 16">
                                <path
                                    d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85zm-5.242 1.106a5 5 0 1 1 0-10 5 5 0 0 1 0 10z" />
                            </svg>
                        </button>
                    </div>
                </form>



                <!-- ACTION -->
                <!-- ACTION -->
                <div class="d-flex align-items-center gap-3 ms-lg-4 action-group">

                    <!-- Cart -->
                    <a href="#" class="text-white fs-5 position-relative cart-wrap">
                        <span class="iconify cart-icon" data-icon="tdesign:cart"></span>
                        {{-- <span class="cart-badge" id="cartBadge">3</span> --}}
                    </a>

                    <!-- Divider -->
                    <span class="action-divider"></span>

                    <!-- Auth (BEFORE LOGIN) -->
                    <!-- Auth (BEFORE LOGIN) -->
                    <div id="authButtons" class="d-flex gap-4">
                        <a href="/login" class="btn btn-masuk">Masuk</a>
                        <a href="/register" class="btn btn-register">Daftar</a>
                    </div>

                    <!-- User (AFTER LOGIN) -->
                    <div class="user-dropdown" id="userDropdown" style="display:none">
                        <div class="user-trigger" onclick="toggleDropdown(event)">
                            <img src="https://i.pravatar.cc/100?img=3" class="user-avatar">
                            <span class="username">Username</span>
                        </div>

                        <div class="dropdown-menu-custom">
                            <span class="dropdown-arrow"></span>
                            <a href="/account">Akun Saya</a>
                            <a href="#">Pesanan Saya</a>
                            <a href="#" class="logout" onclick="logoutUser()">Keluar</a>
                        </div>
                    </div>



                </div>


            </div>
        </div>
    </nav>

</header>

<script src="https://code.iconify.design/3/3.1.1/iconify.min.js"></script>
<script>
    const authButtons = document.getElementById('authButtons');
    const userDropdown = document.getElementById('userDropdown');
    const usernameSpan = userDropdown.querySelector('.username');
    const userAvatar = userDropdown.querySelector('.user-avatar');

    /**
     * Ambil token dari localStorage
     */
    function getToken() {
        return localStorage.getItem('access_token');
    }

    /**
     * Render navbar sesuai status login
     */
    async function renderNavbar() {
        const token = getToken();

        if (!token) {
            // Belum login → tampilkan tombol Masuk/Daftar, sembunyikan dropdown
            authButtons.style.setProperty('display', 'flex', 'important');
            userDropdown.style.setProperty('display', 'none', 'important');
            return;
        }

        try {
            // Ambil data user dari API /api/me
            const response = await fetch('{{ url('/api/me') }}', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${token}`
                }
            });

            const data = await response.json();

            if (response.ok && data.success && data.data.user) {
                // Login valid → tampilkan dropdown, sembunyikan tombol
                authButtons.style.setProperty('display', 'none', 'important');
                userDropdown.style.setProperty('display', 'flex', 'important');

                // Set username & avatar
                usernameSpan.textContent = data.data.user.name || 'User';
                userAvatar.src = data.data.user.avatar_url || 'https://i.pravatar.cc/100?img=3';
            } else {
                // Token invalid → reset navbar
                localStorage.removeItem('access_token');
                authButtons.style.setProperty('display', 'flex', 'important');
                userDropdown.style.setProperty('display', 'none', 'important');
            }
        } catch (err) {
            console.error('Error fetching user:', err);
            localStorage.removeItem('access_token');
            authButtons.style.setProperty('display', 'flex', 'important');
            userDropdown.style.setProperty('display', 'none', 'important');
        }
    }

    /**
     * Toggle dropdown menu
     */
    function toggleDropdown(e) {
        e.stopPropagation();
        userDropdown.classList.toggle('open');
    }

    /**
     * Logout user via API
     */
    async function logoutUser() {
        const token = getToken();
        if (!token) return;

        try {
            const res = await fetch('{{ url('/api/logout') }}', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${token}`,
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            const data = await res.json();

            if (res.ok && data.success) {
                // Logout berhasil → hapus token & reset navbar
                localStorage.removeItem('access_token');
                userDropdown.classList.remove('open');
                renderNavbar();
            } else {
                alert(data.message || 'Logout gagal');
            }
        } catch (err) {
            console.error('Logout error:', err);
            alert('Terjadi kesalahan saat logout');
        }
    }

    /**
     * Login user (panggil saat submit login form)
     */
    async function loginUser(email_or_phone, password) {
        try {
            const res = await fetch('{{ url('/api/login') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    email_or_phone,
                    password
                })
            });

            const data = await res.json();

            if (res.ok && data.success && data.data.access_token) {
                // Simpan token → render navbar
                localStorage.setItem('access_token', data.data.access_token);
                renderNavbar();
            } else {
                alert(data.message || 'Login gagal');
            }
        } catch (err) {
            console.error('Login error:', err);
            alert('Terjadi kesalahan saat login');
        }
    }

    // Tutup dropdown saat klik di luar atau scroll
    document.addEventListener('click', () => userDropdown.classList.remove('open'));
    window.addEventListener('scroll', () => userDropdown.classList.remove('open'));

    // Render navbar saat page load
    document.addEventListener('DOMContentLoaded', renderNavbar);
</script>
