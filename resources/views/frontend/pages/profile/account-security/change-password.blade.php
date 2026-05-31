<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/png" href="{{ asset('images/logo/icon-title.png') }}">
    <title>Ubah Kata Sandi</title>


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
        <div class="text-center mt-3">
            <h5 class="fw-semibold text-custom-green">
                Ubah Kata Sandi
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
                Masukan Kata Sandi Lama dan <br> Kata Sandi Baru Anda
            </p>

            <!-- FORM -->
            <form method="POST" action="">
                @csrf

                <input type="hidden" name="type" value="">

                <!-- PASSWORD Lama -->
                <div class="mb-3 text-start">
                    <label class="form-label">Kata Sandi Lama</label>
                    <div class="password-wrapper">
                        <input type="password" name="oldPassword" class="form-control"
                            placeholder="Masukan Kata Sandi Lama Anda" id="oldPassword">
                        <span class="password-toggle" data-target="oldPassword">
                            <span class="iconify" data-icon="weui:eyes-on-filled"></span>
                        </span>
                        <div class="invalid-feedback"></div> <!-- di luar password-wrapper -->
                    </div>
                </div>

                <!-- PASSWORD -->
                <div class="mb-3 text-start">
                    <label class="form-label">Kata Sandi Baru</label>
                    <div class="password-wrapper">
                        <input type="password" name="newPassword" class="form-control"
                            placeholder="Masukan Kata Sandi Baru Anda" id="newPassword">
                        <span class="password-toggle" data-target="newPassword">
                            <span class="iconify" data-icon="weui:eyes-on-filled"></span>
                        </span>
                        <div class="invalid-feedback"></div> <!-- di luar password-wrapper -->
                    </div>
                </div>

                <!-- KONFIRMASI PASSWORD -->
                <div class="mb-3 text-start">
                    <label class="form-label">Konfirmasi Kata Sandi Baru</label>
                    <div class="password-wrapper">
                        <input type="password" name="password_confirmation" class="form-control"
                            placeholder="Masukan Konfirmasi Kata Sandi Baru Anda" id="password_confirmation">
                        <div class="invalid-feedback"></div>
                        <span class="password-toggle" data-target="password_confirmation">
                            <span class="iconify" data-icon="weui:eyes-on-filled"></span>
                        </span>
                    </div>
                </div>

                <button type="submit" id="changePasswordBtn"
                    class="btn btn-login w-100 text-white d-flex align-items-center justify-content-center"
                    data-btn-target="oldPassword,newPassword,password_confirmation">
                    SELESAI
                </button>
            </form>
        </div>

    </main>

    <!-- FOOTER -->
    <footer class="footer mt-3 px-5" style="min-height: 50px;"></footer>

</body>
<!-- ICONIFY (WAJIB biar icon muncul) -->
<script src="https://code.iconify.design/3/3.1.1/iconify.min.js"></script>
<script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
<script src="{{ asset('js/eye-on.js') }}"></script>
<script>
    const form = document.querySelector('form');

    const oldPassword = document.getElementById('oldPassword');
    const newPassword = document.getElementById('newPassword');
    const confirmPassword = document.getElementById('password_confirmation');

    const changePasswordBtn = document.getElementById('changePasswordBtn');

    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        const token = localStorage.getItem('token');
        const updateToken = sessionStorage.getItem('update_token');

        // 1. CEK LOGIN
        if (!token) {
            alert('Silakan login terlebih dahulu');
            window.location.href = "/login";
            return;
        }

        // 2. CEK OTP TOKEN
        if (!updateToken || updateToken === 'null' || updateToken === 'undefined') {
            alert('Session verifikasi habis, ulangi OTP');
            window.location.href = "/verify-otp";
            return;
        }

        // 3. VALIDASI PASSWORD
        if (newPassword.value !== confirmPassword.value) {
            alert('Konfirmasi password tidak sama');
            return;
        }

        if (newPassword.value.length < 8) {
            alert('Password minimal 8 karakter');
            return;
        }

        try {
            changePasswordBtn.disabled = true;
            changePasswordBtn.innerText = 'Memproses...';

            const response = await fetch('/api/auth/change-password', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    update_token: updateToken,
                    current_password: oldPassword.value,
                    password: newPassword.value,
                    password_confirmation: confirmPassword.value
                })
            });

            const result = await response.json();

            if (!response.ok) {
                alert(result.message || 'Gagal update password');
                changePasswordBtn.disabled = false;
                changePasswordBtn.innerText = 'SELESAI';
                return;
            }

            alert('Password berhasil diperbarui');

            // clear session
            sessionStorage.removeItem('update_token');
            sessionStorage.removeItem('verify_email');
            sessionStorage.removeItem('verify_target');

            window.location.href = "/profile";

        } catch (error) {
            console.error(error);
            alert('Terjadi kesalahan');

            changePasswordBtn.disabled = false;
            changePasswordBtn.innerText = 'SELESAI';
        }
    });
</script>

</html>
