<style>
    /* Navbar Background */
    .navbar-bg {
        background-color: var(--primary-500);
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
            <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="#">
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
                    </a>

                    <!-- Divider -->
                    <span class="action-divider"></span>

                    <!-- Auth -->
                    <a href="#" class="btn btn-masuk">Masuk</a>
                    <a href="#" class="btn btn-register">Daftar</a>


                </div>


            </div>
        </div>
    </nav>

</header>

<script src="https://code.iconify.design/3/3.1.1/iconify.min.js"></script>
