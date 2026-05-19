<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ami Jaya Jampang</title>

    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>



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

        .section-payment {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding-top: 60px;
            padding-bottom: 60px;
        }

        .payment-card {
            width: 100%;
            max-width: 600px;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .payment-icon {
            width: 40px;
            height: 40px;
            background-color: #ffb37c;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: white;
            font-size: 16px;
        }

        .payment-timer {
            background-color: #198754;
            color: #fff;
            border-radius: 6px;
            padding: 5px 5px;
            font-size: 10px;

        }

        .text-timer {
            color: #198754;
            font-size: 11px;
            line-height: 1.1;
        }

        .custom-border {
            border-bottom: 2px solid #adadad;
        }

        .copy-btn:hover {
            transform: scale(1.1);
            transition: 0.2s ease;
        }

        .copy-feedback {
            animation: fadeIn 0.2s ease-in-out;
        }

        .copy-btn:active {
            transform: scale(0.9);
        }

        .note-list {
            list-style: none;
            padding-left: 0;
        }

        .note-list li {
            position: relative;
            padding-left: 14px;
            margin-bottom: 4px;
        }

        .note-list li::before {
            content: "-";
            position: absolute;
            left: 0;
            color: #6c757d;
        }


        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-2px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
    <nav class="navbar navbar-expand-lg navbar-bg fixed-top">
        <div class="container">
            <!-- Logo -->
            <a class="navbar-brand d-flex align-items-center" href="#">
                <img src="{{ asset('images/logo/logo-putih.png') }}" alt="Logo" class="img-fluid navbar-logo">
            </a>
        </div>
    </nav>
    <section class="section-payment d-flex align-items-center justify-content-center">
        <div class="payment-card position-relative bg-white">

            <div class="d-flex justify-content-between align-items-start mb-3 custom-border">

                <!-- KIRI -->
                <div class="d-flex align-items-start gap-3">
                    <div class="payment-icon">L</div>

                    <div>
                        <h6 class="fw-bold mb-0">Bayar Sebelum</h6>
                        <small class="text-muted">27 Januari 2026, 21:00 WIB</small>
                    </div>
                </div>

                <!-- KANAN -->
                <div class="d-flex align-items-center justify-content-center text-center">
                    <div>
                        <div class="fw-bold payment-timer mb-1">15</div>
                        <p class="text-timer mb-0">Menit</p>
                    </div>

                    <div class="fw-bold text-timer mx-1 mb-3">:</div>

                    <div>
                        <div class="fw-bold payment-timer mb-1">00</div>
                        <p class="text-timer mb-0">Detik</p>
                    </div>
                </div>

            </div>
            <!-- Virtual Account -->
            <div class="mb-3">
                <small class="text-muted">Nomor Virtual Account</small>

                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-2">
                        <span class="fw-semibold copy-value">8077708002211122</span>

                        <span class="iconify text-custom-green copy-btn" data-icon="solar:notes-outline"
                            style="font-size:18px; cursor:pointer;" title="Salin">
                        </span>

                        <span class="copy-feedback text-success small ms-1" style="display:none;">
                            Berhasil disalin
                        </span>

                    </div>

                    <img src="{{ asset('images/payments/bank/bca.png') }}" height="25" alt="BCA">
                </div>
            </div>

            <!-- Total Tagihan -->
            <div class="mb-3">
                <small class="text-muted">Total Tagihan</small>

                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-2">
                        <span class="fw-bold text-custom-green copy-value">Rp.153.000</span>

                        <span class="iconify text-custom-green copy-btn" data-icon="solar:notes-outline"
                            style="font-size:18px; cursor:pointer;" title="Salin">
                        </span>

                        <span class="copy-feedback text-success small ms-1" style="display:none;">
                            Berhasil disalin
                        </span>

                    </div>

                    <a href="#" class="text-custom-green small fw-semibold">Lihat Detail</a>
                </div>
            </div>
            <hr>
            <!-- Notes -->
            <ul class="note-list small mb-4">
                <li>Transfer Virtual Account hanya bisa dilakukan dari <br> Bank yang kamu pilih</li>
                <li>Transaksi kamu baru akan diteruskan ke penjual setelah <br>pembayaran berhasil diverifikasi</li>
            </ul>
            <!-- Buttons -->
            <div class="d-flex justify-content-center gap-5">
                <button class="btn btn-custom-green fw-medium px-4 py-2">
                    Lihat Cara Bayar
                </button>
                <button class="btn btn-custom-green fw-medium px-4 py-2">
                    Cek Status Bayar
                </button>
            </div>
        </div>
    </section>
    @include('frontend.partials.footer')

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            document.querySelectorAll('.copy-btn').forEach(btn => {
                btn.addEventListener('click', function() {

                    // ambil teks dari span sebelum icon
                    const text = this.previousElementSibling.innerText.trim();

                    // copy (fallback aman)
                    const tempInput = document.createElement('input');
                    tempInput.value = text;
                    document.body.appendChild(tempInput);
                    tempInput.select();
                    document.execCommand('copy');
                    document.body.removeChild(tempInput);

                    // reset semua feedback
                    document.querySelectorAll('.copy-feedback').forEach(f => f.style.display =
                        'none');

                    // tampilkan feedback milik item ini
                    const feedback = this.nextElementSibling;
                    if (feedback) feedback.style.display = 'inline';

                    // efek visual
                    this.classList.add('text-success');

                    setTimeout(() => {
                        this.classList.remove('text-success');
                        if (feedback) feedback.style.display = 'none';
                    }, 1500);
                });
            });

        });
    </script>

    <script src="https://code.iconify.design/3/3.1.1/iconify.min.js"></script>




</body>

</html>
