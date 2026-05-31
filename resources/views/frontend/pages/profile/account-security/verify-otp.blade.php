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
                <a href="#" class="text-white text-decoration-none">
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
                Kode Verifikasi
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
                Masukan Kode Verifikasi yang kami <br>
                kirimkan ke Email Anda
            </p>

            <!-- FORM -->
            <form method="POST" action="">
                @csrf
                <div class="mb-3">
                    <label class="form-label ">Kode Verifikasi</label>
                    <input id="kode" type="text" name="kode" class="form-control"
                        placeholder="Masukkan Kode Verifikasi Anda" required>
                </div>
                <div class="text-muted text-center mb-3">
                    Belum menerima kode?
                    <a href="#" id="resend-code" class="text-primary fw-normal text-decoration-none">
                        Kirim Kode
                    </a>
                </div>

                <button type="submit" id="verifyOtpBtn"
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
    const kode = document.getElementById('kode');
    const verifyOtpBtn = document.getElementById('verifyOtpBtn');

    // ACTIVE BUTTON
    kode.addEventListener('input', function() {
        if (kode.value.trim() !== '') {
            verifyOtpBtn.classList.add('active');
        } else {
            verifyOtpBtn.classList.remove('active');
        }
    });

    // SUBMIT OTP
    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        const token = localStorage.getItem('token');

        if (!token) {
            alert('Silakan login terlebih dahulu');
            return;
        }

        const target = sessionStorage.getItem('verify_target');

        let purpose = '';

        if (target === 'email') purpose = 'update_email';
        if (target === 'phone') purpose = 'update_phone';
        if (target === 'password') purpose = 'forgot_password';

        try {
            verifyOtpBtn.disabled = true;
            verifyOtpBtn.innerText = 'Memproses...';

            // VERIFY OTP
            const response = await fetch('/api/otp/verify', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    otp: kode.value,
                    purpose: purpose,
                    email: sessionStorage.getItem('verify_email')
                })
            });

            const result = await response.json();
            console.log(result);

            if (!response.ok) {
                console.log(result);
                alert(result.message || 'OTP tidak valid');
                verifyOtpBtn.disabled = false;
                verifyOtpBtn.innerText = 'BERIKUTNYA';
                return;
            }

            // SIMPAN UPDATE TOKEN
            sessionStorage.setItem(
                'update_token',
                result.data.update_token
            );

            // REDIRECT
            if (target === 'email') {
                window.location.href = "{{ route('change-email') }}";
                return;
            }

            if (target === 'phone') {
                window.location.href = "{{ route('change-phone') }}";
                return;
            }

            if (target === 'password') {
                window.location.href = "{{ route('change-password') }}";
                return;
            }

        } catch (error) {
            console.error(error);

            alert('Terjadi kesalahan');

            verifyOtpBtn.disabled = false;
            verifyOtpBtn.innerText = 'BERIKUTNYA';
        }
    });
</script>
<script>
    document.getElementById('resend-code')
        .addEventListener('click', async function(e) {

            e.preventDefault();

            const token = localStorage.getItem('token');
            const target = sessionStorage.getItem('verify_target');

            let purpose = '';

            if (target === 'email') purpose = 'update_email';
            if (target === 'phone') purpose = 'update_phone';
            if (target === 'password') purpose = 'forgot_password';

            try {

                const btn = this;

                btn.innerText = 'Mengirim...';
                btn.style.pointerEvents = 'none';

                const res = await fetch('/api/otp/request', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        purpose: purpose,
                        email: sessionStorage.getItem('verify_email')
                    })
                });

                const result = await res.json();

                console.log(result);

                if (!res.ok) {
                    alert(result.message || 'Gagal mengirim ulang OTP');
                    return;
                }

                alert('Kode OTP berhasil dikirim ulang');

            } catch (err) {

                console.error(err);

                alert('Terjadi kesalahan server');

            } finally {

                const btn = document.getElementById('resend-code');

                btn.innerText = 'Kirim Kode';
                btn.style.pointerEvents = 'auto';
            }

        });
</script>

</html>
