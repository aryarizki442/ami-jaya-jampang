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
        color: #fff;
    }

    .user-trigger .username {
        max-width: 120px;
        /* sesuaikan */
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        display: block;
    }

    /* USER AVATAR */
    .user-avatar {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        margin-right: 8px;
    }

    /* ================= USER DROPDOWN ================= */

    .dropdown-menu-custom {
        display: none;
        position: absolute;
        top: 62px;
        right: 0;
        background: #fff;
        border-radius: 14px;
        min-width: 190px;
        padding: 8px;
        box-shadow:
            0 12px 35px rgba(0, 0, 0, 0.12);

        z-index: 99999;
        overflow: visible;
    }

    /* MODERN ARROW */
    .dropdown-menu-custom::before {
        content: "";
        position: absolute;
        top: -10px;
        right: 18px;
        width: 18px;
        height: 18px;
        background: white;
        transform: rotate(45deg);
    }

    /* HIDE OLD ARROW */
    .dropdown-arrow {
        display: none;
    }

    /* SHOW */
    .user-dropdown.open .dropdown-menu-custom {
        display: block;
    }

    /* LINK */
    .dropdown-menu-custom a {

        display: flex;

        align-items: center;

        padding: 12px 14px;

        font-size: 14px;

        color: #333;

        text-decoration: none;

        border-radius: 10px;

        transition: .2s;
    }

    .dropdown-menu-custom a:hover {
        background: #f5f7f8;
    }

    /* LOGOUT */
    .dropdown-menu-custom .logout {
        color: #e74c3c;
        font-weight: 600;
    }

    .modal-header,
    .modal-footer {
        border: none !important;
    }

    .modal-content {
        border: none !important;
        box-shadow: none !important;
    }


    /* =========================================================
   RESPONSIVE NAVBAR - MODERN MOBILE UI
========================================================= */

    @media (max-width: 992px) {

        /* container */
        .container-fluid.px-5 {
            padding-left: 18px !important;
            padding-right: 18px !important;
        }

        /* ================= TOP BAR ================= */

        header .border-bottom .container-fluid {
            padding-top: 10px !important;
            padding-bottom: 10px !important;

            flex-direction: column;
            align-items: center !important;
            gap: 10px;
            text-align: center;
        }

        header .border-bottom .container-fluid .d-flex {
            justify-content: center;
            flex-wrap: wrap;
            gap: 14px !important;
        }

        /* ================= NAVBAR ================= */

        .navbar {
            padding-top: 14px;
            padding-bottom: 14px;
        }

        .navbar-brand img {
            width: 125px;
        }

        /* hamburger */
        .navbar-toggler {
            border: none !important;
            box-shadow: none !important;
            padding: 0;
        }

        .navbar-toggler:focus {
            box-shadow: none !important;
        }

        /* collapse area */
        .navbar-collapse {

            margin-top: 18px;

            background: rgba(255, 255, 255, 0.08);

            backdrop-filter: blur(14px);

            border: 1px solid rgba(255, 255, 255, 0.08);

            border-radius: 22px;

            padding: 18px;

            animation: fadeSlide .25s ease;
        }

        @keyframes fadeSlide {
            from {
                opacity: 0;
                transform: translateY(-8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ================= SEARCH ================= */

        .navbar form {
            padding-left: 0 !important;
            padding-right: 0 !important;
            margin: 0 !important;
        }

        .navbar .input-group {
            position: relative;
        }

        .navbar form input {

            height: 52px;

            border-radius: 16px !important;

            border: none !important;

            font-size: 14px;

            padding-left: 16px;

            padding-right: 58px !important;

            box-shadow: none !important;
        }

        .navbar form input:focus {
            box-shadow: 0 0 0 4px rgba(255, 255, 255, 0.12) !important;
        }

        .btn-search {

            width: 42px;

            height: 42px;

            border-radius: 12px !important;

            padding: 0 !important;

            right: 5px !important;

            transition: .2s;
        }

        .btn-search:hover {
            transform: translateY(-50%) scale(1.03);
        }

        /* ================= ACTION GROUP ================= */

        .action-group {

            margin-top: 18px;

            width: 100%;

            display: flex;

            flex-direction: column;

            gap: 14px !important;

            align-items: stretch !important;
        }

        .action-divider {
            display: none;
        }

        /* ================= CART ================= */

        .cart-wrap {

            width: 100%;

            margin-right: 0 !important;

            background: rgba(255, 255, 255, 0.08);

            border-radius: 16px;

            padding: 14px 18px;

            justify-content: center;

            transition: .2s;
        }

        .cart-wrap:hover {
            background: rgba(255, 255, 255, 0.12);
        }

        /* ================= AUTH BUTTON ================= */

        #authButtons {

            width: 100%;

            display: flex;

            flex-direction: column;

            gap: 12px !important;
        }

        #authButtons .btn {

            width: 100%;

            height: 48px;

            border-radius: 14px;

            font-weight: 600;
        }

        /* ================= USER DROPDOWN ================= */

        .user-dropdown {
            width: 100%;
        }

        .user-trigger {

            width: 100%;

            padding: 14px 16px;

            background: rgba(255, 255, 255, 0.08);

            border-radius: 16px;

            justify-content: space-between;

            transition: .2s;
        }

        .user-trigger:hover {
            background: rgba(255, 255, 255, 0.12);
        }

        .user-avatar {
            width: 38px;
            height: 38px;
        }

        .dropdown-menu-custom {

            position: static;

            display: none;

            width: 100%;

            margin-top: 12px;

            background: #fff;

            border-radius: 16px;

            overflow: hidden;

            box-shadow:
                0 10px 30px rgba(0, 0, 0, 0.12);
        }

        .dropdown-arrow {
            display: none;
        }

        .dropdown-menu-custom a {

            padding: 14px 16px;

            font-size: 14px;

            transition: .2s;
        }

        .dropdown-menu-custom a:hover {
            background: #f6f6f6;
        }

        /* dropdown open */
        .user-dropdown.open .dropdown-menu-custom {
            display: block;
        }
    }

    .notif-wrap {
        position: relative;
    }

    .notif-dropdown {

        position: absolute;

        top: 38px;
        right: 0;

        width: 420px;

        background: #fff;

        border-radius: 8px;

        display: none;

        z-index: 99999;

        overflow: hidden;
    }

    .notif-dropdown.show {
        display: block;
    }

    .notif-dropdown::before {

        content: "";

        position: absolute;

        top: -10px;
        right: 30px;

        width: 20px;
        height: 20px;

        background: white;

        transform: rotate(45deg);
    }

    .notif-header {

        padding: 12px 18px;

        background: #f3f3f3;

        border-bottom: 1px solid #ddd;

        font-weight: 500;
    }

    .notif-footer {

        display: block;

        text-align: center;

        padding: 12px;

        font-weight: 600;

        text-decoration: none;

        color: #222;

        border-top: 1px solid #ddd;
    }

    .notif-footer:hover {
        background: #f8f8f8;
    }

    .notif-item-mini {

        display: flex;

        gap: 12px;

        padding: 14px 18px;

        border-bottom: 1px solid #eee;
    }

    .notif-item-mini:hover {
        background: #f8f8f8;
    }

    .notif-icon-mini {

        width: 44px;
        height: 44px;

        border-radius: 50%;

        display: flex;
        align-items: center;
        justify-content: center;

        flex-shrink: 0;
    }

    .notif-content-mini {
        flex: 1;
    }

    .notif-title-mini {
        font-weight: 500;
    }

    .notif-time-mini {
        font-size: 12px;
        color: #999;
    }

    .notif-badge {
        position: absolute;

        top: -10px;
        right: -12px;

        min-width: 18px;
        height: 18px;
        padding: 0 5px;

        border-radius: 999px;

        background: #FF0000;

        color: #fff;

        font-size: 10px;
        font-weight: 600;

        display: flex;
        align-items: center;
        justify-content: center;

        border: 2px solid #1F7D53;
        /* warna navbar */
    }

    .notif-waiting {
        background: #f5f5f5;
        color: #6c757d;
    }

    .notif-process {
        background: #fff5e6;
        color: #f59e0b;
    }

    .notif-shipped {
        background: #e6f1fb;
        color: #3b82f6;
    }

    .notif-finished {
        background: #e9f8ee;
        color: #22c55e;
    }

    .notif-cancelled {
        background: #ffeaea;
        color: #ef4444;
    }

    /* =========================================================
   EXTRA SMALL DEVICE
========================================================= */

    @media (max-width: 576px) {

        .container-fluid.px-5 {
            padding-left: 14px !important;
            padding-right: 14px !important;
        }

        header .small {
            font-size: 11px !important;
        }

        .navbar-brand img {
            width: 112px;
        }

        .navbar-collapse {
            padding: 16px;
            border-radius: 18px;
        }

        .navbar form input {
            height: 50px;
            font-size: 13px;
        }

        .btn-search {
            width: 40px;
            height: 40px;
        }

        .cart-wrap {
            padding: 13px 16px;
        }

        .user-trigger {
            padding: 13px 14px;
        }

        .dropdown-menu-custom a {
            padding: 13px 14px;
        }
    }
</style>

<header class="navbar-bg">

    <!-- TOP BAR -->
    <div class="border-bottom border-light">
        <div class="container-fluid px-5 py-1 d-flex justify-content-between align-items-center pt-2 pb-2">
            <span class="text-white small">
                Selamat Datang Di Toko Beras Jampang
            </span>

            <div class="d-flex gap-3 ">
                <div class="notif-wrap" id="notifWrap">

                    <a href="javascript:void(0)" id="notifToggle"
                        class="text-white text-decoration-none small d-flex align-items-center">

                        <div class="position-relative d-inline-flex align-items-center">

                            <iconify-icon icon="mingcute:notification-line" width="20" height="20">
                            </iconify-icon>

                            <span class="notif-badge d-none" id="notifBadge">
                                0
                            </span>

                        </div>

                        <span style="margin-left:10px;">Notifikasi</span>

                    </a>

                    <div class="notif-dropdown shadow" id="notifDropdown">

                        <div class="notif-header">
                            Notifikasi Baru Diterima
                        </div>

                        <div id="notifDropdownList">

                            <div class="p-3 text-center text-secondary">
                                Memuat...
                            </div>

                        </div>

                        <a href="{{ route('notification') }}" class="notif-footer">
                            Tampilkan Semua
                        </a>

                    </div>

                </div>
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
                <div class="d-flex align-items-center gap-3 ms-lg-4 action-group">

                    <!-- Cart -->
                    @include('frontend.components.cart-badge')

                    <!-- Divider -->
                    <span class="action-divider"></span>

                    <!-- Auth (BEFORE LOGIN) -->
                    <div id="authButtons" class="d-flex gap-4">
                        <a href="/login" class="btn btn-masuk">Masuk</a>
                        <a href="/send-email" class="btn btn-register">Daftar</a>
                    </div>

                    <!-- User (AFTER LOGIN) -->
                    <div class="user-dropdown" id="userDropdown" style="display:none">
                        <div class="user-trigger" onclick="toggleDropdown(event)">
                            <img src="{{ asset('images/home/user/user-group.png') }}" class="user-avatar">
                            <span class="username">Username</span>
                        </div>

                        <div class="dropdown-menu-custom">
                            <span class="dropdown-arrow"></span>
                            <a href="{{ route('profile') }}">Akun Saya</a>
                            <a href="{{ route('orders.all') }}">Pesanan Saya</a>
                            <a href="#" id="logoutBtn" class="logout">
                                Keluar
                            </a>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </nav>
</header>

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
    const authButtons = document.getElementById('authButtons');
    const userDropdown = document.getElementById('userDropdown');
    const usernameSpan = userDropdown.querySelector('.username');
    const userAvatar = userDropdown.querySelector('.user-avatar');

    function setUser(userData) {
        if (usernameSpan && userData.name) usernameSpan.textContent = userData.name;
        if (userAvatar && userData.avatar) userAvatar.src = userData.avatar;
    }
    /**
     * Ambil token dari localStorage
     */
    function getToken() {
        return localStorage.getItem('token');
    }


    /**
     * Render navbar sesuai status login
     */
    async function renderNavbar() {

        const token = localStorage.getItem('token');

        console.log('TOKEN:', token);

        // =========================
        // BELUM LOGIN
        // =========================
        if (!token) {

            authButtons.style.display = 'flex';
            userDropdown.style.display = 'none';

            return;
        }

        try {

            const response = await fetch('/api/me', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Authorization': 'Bearer ' + token
                }
            });

            const data = await response.json();

            console.log('ME:', JSON.stringify(data, null, 2));

            // =========================
            // LOGIN VALID
            // =========================
            if (response.ok) {
                authButtons.style.setProperty('display', 'none', 'important');
                userDropdown.style.setProperty('display', 'flex', 'important');

                const localUser = JSON.parse(localStorage.getItem('user'));
                const user = data.data?.user || localUser;

                // username
                usernameSpan.textContent = user?.name || 'User';

                // avatar - pakai avatarUrl() helper jika tersedia, fallback manual
                const rawAvatar = user?.avatar || null;
                if (typeof avatarUrl === 'function') {
                    userAvatar.src = avatarUrl(rawAvatar);
                } else {
                    if (!rawAvatar) {
                        userAvatar.src = "{{ asset('images/home/user/user-group.png') }}";
                    } else if (rawAvatar.startsWith('http')) {
                        userAvatar.src = rawAvatar;
                    } else if (rawAvatar.startsWith('/storage/')) {
                        userAvatar.src = window.location.origin + rawAvatar;
                    } else {
                        userAvatar.src = window.location.origin + '/storage/' + rawAvatar;
                    }
                }
            }
            // =========================
            // TOKEN INVALID
            // =========================
            else {

                authButtons.style.setProperty(
                    'display',
                    'flex',
                    'important'
                );

                userDropdown.style.setProperty(
                    'display',
                    'none',
                    'important'
                );

            }

        } catch (err) {

            console.error(err);

            // fallback tetap tampilkan user jika token ada
            const localUser = JSON.parse(
                localStorage.getItem('user')
            );

            if (localUser) {

                authButtons.style.setProperty(
                    'display',
                    'none',
                    'important'
                );

                userDropdown.style.setProperty(
                    'display',
                    'flex',
                    'important'
                );

                usernameSpan.textContent =
                    localUser?.name || 'User';

                userAvatar.src =
                    localUser?.avatar ||
                    "{{ asset('images/home/user/user-group.png') }}";

            } else {

                authButtons.style.setProperty(
                    'display',
                    'flex',
                    'important'
                );

                userDropdown.style.setProperty(
                    'display',
                    'none',
                    'important'
                );

            }
        }
    }

    /**
     * Toggle dropdown menu
     */
    function toggleDropdown(e) {
        e.stopPropagation();
        userDropdown.classList.toggle('open');
    }


    // =========================
    // OPEN LOGOUT MODAL
    // =========================
    document.querySelector('.logout').addEventListener('click', function(e) {

        e.preventDefault();

        const modal = new bootstrap.Modal(
            document.getElementById('logoutModal')
        );

        modal.show();
    });

    // =========================
    // CONFIRM LOGOUT
    // =========================
    document.getElementById('confirmLogoutBtn')
        .addEventListener('click', async function() {

            const token = localStorage.getItem('token');

            // kalau token tidak ada
            if (!token) {

                localStorage.removeItem('token');
                localStorage.removeItem('read_notifications');

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

                // walaupun token invalid tetap logout frontend
                if (res.status === 401) {

                    localStorage.removeItem('token');
                    localStorage.removeItem('read_notifications');

                    window.location.href = '/login';

                    return;
                }

                // tutup modal
                bootstrap.Modal
                    .getInstance(document.getElementById('logoutModal'))
                    ?.hide();

                // hapus token
                localStorage.removeItem('token');

                // redirect login
                window.location.href = '/login';

            } catch (err) {

                console.error(err);

                alert('Logout gagal');
            }
        });

    /**
     * Login user (panggil saat submit login form)
     */
    async function loginUser(email_or_phone, password) {

        try {

            const res = await fetch('/api/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    email_or_phone,
                    password
                })
            });

            const data = await res.json();

            console.log('LOGIN:', data);

            // sesuaikan dengan response API kamu
            const token =
                data.token ||
                data.access_token ||
                data.data?.token ||
                data.data?.access_token;

            if (res.ok && token) {

                localStorage.setItem('token', token);

                await renderNavbar();

            } else {

                alert('Login gagal');

            }

        } catch (err) {

            console.error(err);

        }
    }

    const notifBadge = document.getElementById('notifBadge');

    function getNotifIconAndClass(status) {

        let iconClass = 'notif-process';
        let icon = 'mdi:bell-outline';
        let title = 'Update Pesanan';

        switch (status) {

            case 'awaiting_payment':
            case 'pending':
                iconClass = 'notif-waiting';
                icon = 'mdi:clock-outline';
                title = 'Menunggu pembayaran anda';
                break;

            case 'paid':
                iconClass = 'notif-process';
                icon = 'mdi:cog-outline';
                title = 'Pesanan sedang diproses';
                break;

            case 'processing':
                iconClass = 'notif-process';
                icon = 'mdi:cog-outline';
                title = 'Pesanan sedang diproses';
                break;

            case 'shipped':
                iconClass = 'notif-shipped';
                icon = 'mdi:truck-delivery';
                title = 'Pesanan sedang dikirim';
                break;

            case 'ready_for_pickup':
                iconClass = 'notif-shipped';
                icon = 'mdi:store';
                title = 'Pesanan siap dijemput';
                break;

            case 'completed':
                iconClass = 'notif-finished';
                icon = 'mdi:check-decagram';
                title = 'Pesanan anda selesai';
                break;

            case 'cancelled':
                iconClass = 'notif-cancelled';
                icon = 'mdi:close-circle';
                title = 'Pesanan anda dibatalkan';
                break;

            case 'refunded':
                iconClass = 'notif-process';
                icon = 'mdi:currency-usd-off';
                title = 'Pesanan telah direfund';
                break;

            case 'expired':
                iconClass = 'notif-cancelled';
                icon = 'mdi:timer-off-outline';
                title = 'Pembayaran kedaluwarsa';
                break;
        }

        return {
            iconClass,
            icon,
            title
        };
    }

    function formatShortDate(date) {
        return new Date(date).toLocaleDateString('id-ID', {
            day: 'numeric',
            month: 'short',
            year: 'numeric'
        });
    }
    async function loadNotificationBadge() {
        const notifToggle = document.getElementById('notifToggle');
        const notifDropdown = document.getElementById('notifDropdown');
        const notifDropdownList = document.getElementById('notifDropdownList');
        const token = localStorage.getItem('token');

        notifToggle.addEventListener('click', async function(e) {

            e.preventDefault();
            e.stopPropagation();

            notifDropdown.classList.toggle('show');

            if (notifDropdown.classList.contains('show')) {
                return;
            }
        });

        document.addEventListener('click', function(e) {

            if (!notifDropdown.contains(e.target) &&
                !notifToggle.contains(e.target)) {

                notifDropdown.classList.remove('show');
            }
        });
        if (!token) {
            notifBadge.classList.add('d-none');
            return;
        }

        try {

            const response = await fetch('/api/orders', {
                headers: {
                    Accept: 'application/json',
                    Authorization: `Bearer ${token}`
                }
            });

            const result = await response.json();

            const orders = result?.data?.data || [];

            const readIds = JSON.parse(
                localStorage.getItem('read_notifications') || '[]'
            );

            // hanya notif yang belum dibaca
            const unreadOrders = orders.filter(
                order => !readIds.includes(Number(order.id))
            );

            const unreadCount = unreadOrders.length;

            // badge
            if (unreadCount > 0) {

                notifBadge.textContent = unreadCount;
                notifBadge.classList.remove('d-none');

            } else {

                notifBadge.classList.add('d-none');
            }

            // dropdown hanya tampilkan yang belum dibaca
            const latest = unreadOrders.slice(0, 5);

            let html = '';

            latest.forEach(order => {

                const {
                    iconClass,
                    icon,
                    title
                } =
                getNotifIconAndClass(order.status);

                html += `
                <div class="notif-item-mini">

                    <div class="notif-icon-mini ${iconClass}">
                        <iconify-icon
                            icon="${icon}"
                            width="22"
                            height="22">
                        </iconify-icon>
                    </div>

                    <div class="notif-content-mini">

                        <div class="notif-title-mini">
                            ${title}
                        </div>

                        <div class="notif-time-mini">
                            ${formatShortDate(order.created_at)}
                        </div>

                    </div>

                </div>
            `;
            });

            notifDropdownList.innerHTML = html;

        } catch (err) {

            console.error(err);

            alert(err.message);

            notifDropdownList.innerHTML = `
        <div class="p-3 text-danger text-center">
            Gagal memuat notifikasi
        </div>
    `;
        }
    }

    document.addEventListener('click', () => userDropdown.classList.remove('open'));
    window.addEventListener('scroll', () => userDropdown.classList.remove('open'));
    document.addEventListener('DOMContentLoaded', async () => {

        await renderNavbar();
        await loadNotificationBadge();

    });
</script>
