<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/png" href="{{ asset('images/logo/icon-title.png') }}">
    <title>Toko Beras Jampang</title>

    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">


    <!-- GOOGLE FONT -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"rel="stylesheet">

    <!-- CUSTOM CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/color.css') }}">
    <link rel="stylesheet" href="{{ asset('css/login/login.css') }}">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <link rel="stylesheet" href="{{ asset('css/button.css') }}">


</head>

<body>
    <style>
        .navbar-bg {
            background: linear-gradient(90deg, #0D3523, #1F7D53);
            padding: 10px 0;
            transition: .3s ease;
            z-index: 999;
        }

        .navbar-logo {
            width: 150px;
            height: auto;
        }

        /* Nav Link */
        .navbar .nav-link {
            color: white !important;
            font-weight: 500;
            transition: .3s;
        }

        .navbar .nav-link:hover {
            opacity: .8;
        }

        /* Mobile */
        @media (max-width: 991px) {
            .navbar-collapse {
                background: rgba(13, 53, 35, 0.98);
                margin-top: 12px;
                padding: 15px;
                border-radius: 12px;
            }

            .navbar-nav {
                gap: 10px;
            }

            .navbar-logo {
                width: 120px;
            }
        }

        .title-payment {
            font-size: 28px;
        }

        .payment-card {
            border-radius: 8px;
            border: none;
            font-size: 14px;
        }

        hr {
            margin: 18px 0;
            opacity: 0.3;
        }

        .text-success {
            color: #1a8f5b !important;
        }

        .custom-border {
            border-bottom: 1px solid #000000;
            padding-bottom: 1rem;
            /* jarak ke border */
            margin-bottom: 1rem;
            /* setara mb-3 */
        }
    </style>
    <nav class="navbar navbar-expand-lg navbar-bg fixed-top">
        <div class="container">
            <!-- Logo -->
            <a class="navbar-brand d-flex align-items-center" href="/">
                <img src="{{ asset('images/logo/logo-putih.png') }}" alt="Logo" class="img-fluid navbar-logo">
            </a>
        </div>
    </nav>
    <div class="container mt-5 pt-5">
        <section>
            <div class="d-flex align-items-center mt-0">
                <a href="/payment" class="d-flex align-items-center text-decoration-none text-dark">
                    <i class="ri-arrow-left-line fs-5 me-2"></i>
                    <span class="fw-medium">Kembali</span>
                </a>
            </div>
            <h5 class="fw-bold mt-3 title-payment">Detail Payment</h5>
            <div class=" payment-card">
                <div class="row mb-2">
                    <div class="col fw-semibold">Total Harga</div>
                    <div class="col text-end fw-semibold">Rp. 100.000</div>
                </div>

                <div class="row custom-border">
                    <div class="col text-muted">Total ongkos Kirim</div>
                    <div class="col text-end text-muted">Rp. 50.000</div>
                </div>

                <div class="row mb-2">
                    <div class="col fw-bold">Total Bayar</div>
                    <div class="col text-end fw-bold text-success">Rp. 150.000</div>
                </div>

                <div class="row custom-border">
                    <div class="col text-muted">BCA Virtual Account</div>
                    <div class="col text-end text-muted"> <span class="fw-bold">123456789</span> </div>
                </div>

                <!-- Produk -->
                <h6 class="fw-bold mb-3">Produk Yang Dibeli</h6>

                <div class="row mb-2">
                    <div class="col fw-semibold">
                        1 Karung Beras Putih Premium Rojo Lele
                    </div>
                    <div class="col text-end text-muted">
                        Rp. 100.000
                    </div>
                </div>

                <div class="row mb-1">
                    <div class="col text-muted small">1 X Rp.100.000</div>
                    <div class="col"></div>
                </div>

                <div class="row mb-1">
                    <div class="col text-muted small">Ongkos Kirim</div>
                    <div class="col text-end text-muted">Rp. 50.000</div>
                </div>

                <div class="row mb-3">
                    <div class="col text-muted small">Estimasi Tiba Besok</div>
                    <div class="col"></div>
                </div>

                <!-- Alamat -->
                <h6 class="fw-bold mb-2">Alamat Pengiriman</h6>
                <p class="text-muted mb-0 small">
                    Perumahan Cibinong RT11 RW22 Cibinong, Kabupaten Bogor, Jawa Barat 16345
                </p>

            </div>
    </div>
    </div>
    </section>
    </div>
</body>

</html>
