<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ubah Email</title>


    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!-- CUSTOM CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/color.css') }}">
    <link rel="stylesheet" href="{{ asset('css/login/login.css') }}">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <link rel="stylesheet" href="{{ asset('css/button.css') }}">


    <style>
        .navbar-bg {
            background: linear-gradient(90deg, #0D3523, #1F7D53);
        }

        .title-line {
            width: 250px;
            height: 3px;
            background: #1F7D53;
            margin: 6px auto 0;
            border-radius: 10px;
        }

        .card-custom {
            max-width: 470px;
            border-radius: 10px;
        }

        .btn-custom {
            background: #5FAE8B;
        }

        .btn-custom:hover {
            background: #4c9a78;
        }

        .footer {
            background: linear-gradient(90deg, #0D3523, #1F7D53);
        }
    </style>
</head>

<body class="d-flex flex-column min-vh-100">

    <!-- NAVBAR -->
    <nav class="navbar navbar-bg navbar-expand-lg navbar-dark">
        <div class="container-fluid px-5 d-flex justify-content-between align-items-center">

            <!-- LOGO -->
            <a class="navbar-brand fw-bold d-flex align-items-center" href="/">
                <img src="{{ asset('images/logo/logo-putih.png') }}" width="140">
            </a>

            <!-- RIGHT TEXT -->
            <div>
                <a href="#" class="text-white text-decoration-none ">
                    Butuh Bantuan?
                </a>
            </div>

        </div>
    </nav>

    <!-- CONTENT -->
    <main class="flex-grow-1">

        <!-- TITLE -->
        <div class="text-center mt-5">
            <h5 class="fw-semibold text-custom-green">
                Ubah Email
            </h5>
            <div class="title-line"></div>
        </div>

        <!-- CARD -->
        <div class="card card-custom mx-auto mt-4 p-4 shadow-sm border-0 position-relative">

            <a href="#" class="position-absolute top-0 start-0 m-3 text-dark">
                <iconify-icon icon="mdi:arrow-left" width="24"></iconify-icon>
            </a>
            <!-- ICON -->
            <div class="d-flex justify-content-center mb-3">
                <div class=" d-flex align-items-center justify-content-center">
                    <iconify-icon icon="mdi:security-lock" width="80" style="color:#1F7D53;"></iconify-icon>
                </div>
            </div>

            <!-- TEXT -->
            <p class="text-center  text-muted mb-4">
                Masukan Email Anda <br> Untuk Ubah Email
            </p>

            <!-- FORM -->
            <form method="POST" action="">
                @csrf

                <input type="hidden" name="type" value="">

                <div class="mb-3">
                    <label class="form-label ">Email Baru</label>
                    <input id="email" type="email" name="email" class="form-control"
                        placeholder="Masukkan Email Baru Anda" required>
                </div>

                <button type="submit" id="changeEmailBtn"
                    class="btn btn-login w-100 text-white d-flex align-items-center justify-content-center">
                    SELESAI
                </button>
            </form>
        </div>

    </main>

    <!-- FOOTER -->
    <footer class="footer mt-5 px-5 py-5" style="min-height: 120px;"></footer>

</body>
<!-- ICONIFY (WAJIB biar icon muncul) -->
<script src="https://code.iconify.design/3/3.1.1/iconify.min.js"></script>
<script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
<script>
    const form = document.querySelector('form');
    const email = document.getElementById('email');
    const changeEmailBtn = document.getElementById('changeEmailBtn');

    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        const token = localStorage.getItem('token');


        if (!token) {
            alert('Silakan login terlebih dahulu');
            window.location.href = "/login";
            return;
        }
        const updateToken = sessionStorage.getItem('update_token');

        if (!updateToken || updateToken === 'null' || updateToken === 'undefined') {
            alert('Session verifikasi habis, ulangi OTP');
            window.location.href = "/verify-email";
            return;
        }

        console.log('TOKEN YANG DIPAKAI:', updateToken);
        try {
            changeEmailBtn.disabled = true;
            changeEmailBtn.innerText = 'Memproses...';

            const response = await fetch('/api/update-email', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    update_token: updateToken,
                    new_email: email.value
                })
            });

            const result = await response.json();

            console.log(result);

            if (!response.ok) {
                alert(result.message || 'Gagal update email');
                changeEmailBtn.disabled = false;
                changeEmailBtn.innerText = 'SELESAI';
                return;
            }

            alert('Email berhasil diperbarui');

            // bersihkan session
            sessionStorage.removeItem('update_token');
            sessionStorage.removeItem('verify_email');
            sessionStorage.removeItem('verify_target');

            // redirect
            window.location.href = "/profile";

        } catch (error) {
            console.error(error);
            alert('Terjadi kesalahan');

            changeEmailBtn.disabled = false;
            changeEmailBtn.innerText = 'SELESAI';
        }
    });
</script>

</html>
