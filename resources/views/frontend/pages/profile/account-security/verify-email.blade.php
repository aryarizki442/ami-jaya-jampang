<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/png" href="{{ asset('images/logo/icon-title.png') }}">
    <title>Keamanan Akun</title>


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
                Keamanan Akun
            </h5>
            <div class="title-line"></div>
        </div>

        <!-- CARD -->
        <div class="card card-custom mx-auto mt-4 p-4 shadow-sm border-0">

            <!-- ICON -->
            <div class="d-flex justify-content-center mb-3">
                <div class=" d-flex align-items-center justify-content-center">
                    <iconify-icon icon="mdi:security-lock" width="80" style="color:#1F7D53;"></iconify-icon>
                </div>
            </div>

            <!-- TEXT -->
            <p class="text-center  text-muted mb-4">
                Masukkan kembali email terdaftar lalu <br>
                klik langkah yang kami kirimkan
            </p>

            <!-- FORM -->
            <form method="POST" action="">
                @csrf

                <input type="hidden" id="target" value="{{ $target }}">

                <div class="mb-3">
                    <label class="form-label ">Email</label>
                    <input id="email" type="email" name="email" class="form-control"
                        placeholder="Masukkan Alamat Email Anda" required>
                </div>

                <button type="submit" id="verifyEmailBtn"
                    class="btn btn-login w-100 text-white d-flex align-items-center justify-content-center">
                    BERIKUTNYA
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
    const verifyEmailBtn = document.getElementById('verifyEmailBtn');
    const target = document.getElementById('target');

    email.addEventListener('input', function() {
        verifyEmailBtn.classList.toggle('active', email.value.trim() !== '');
    });

    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        const token = localStorage.getItem('token');

        if (!token) {
            alert('Silakan login terlebih dahulu');
            return;
        }

        try {
            verifyEmailBtn.disabled = true;
            verifyEmailBtn.innerText = 'Memproses...';

            // AMBIL USER
            const meResponse = await fetch('/api/me', {
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json'
                }
            });

            const meResult = await meResponse.json();

            const currentEmail =
                meResult?.data?.email ||
                meResult?.data?.user?.email ||
                meResult?.user?.email;

            if (!currentEmail) {
                alert('Gagal ambil data user');
                return;
            }

            // VALIDASI EMAIL
            if (email.value.trim().toLowerCase() !== currentEmail.toLowerCase()) {
                alert('Masukkan email anda saat ini');
                verifyEmailBtn.disabled = false;
                verifyEmailBtn.innerText = 'BERIKUTNYA';
                return;
            }

            // PURPOSE
            const purposeMap = {
                phone: 'update_phone',
                email: 'update_email',
                password: 'forgot_password'
            };

            const purpose = purposeMap[target.value];

            // REQUEST OTP (HANYA 1X)
            const response = await fetch('/api/otp/request', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    purpose: purpose,
                    email: currentEmail
                })
            });

            const result = await response.json();

            if (!response.ok) {
                console.log(result);
                alert(result.message || 'Verifikasi gagal');
                verifyEmailBtn.disabled = false;
                verifyEmailBtn.innerText = 'BERIKUTNYA';
                return;
            }

            // SIMPAN
            sessionStorage.setItem('verify_email', currentEmail);
            sessionStorage.setItem('verify_target', target.value);

            window.location.href = "{{ route('verify-otp') }}";

        } catch (error) {
            console.error(error);
            alert('Terjadi kesalahan');

            verifyEmailBtn.disabled = false;
            verifyEmailBtn.innerText = 'BERIKUTNYA';
        }
    });
</script>

</html>
