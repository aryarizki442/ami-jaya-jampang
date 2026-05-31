@extends('auth.auth')

@section('title', 'Daftar')

@section('auth')
    <style>
        .send-code {
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            color: var(--info-button-700);
        }

        .send-code:hover {
            color: var(--info-button-500);
        }
    </style>
    <div class="login-page">
        <div class="login-card text-center">

            <!-- LOGO -->
            <div class="login-logo">
                <img src="{{ asset('images/logo/logo-hijau.png') }}" alt="Logo">
            </div>

            <!-- TITLE -->
            <h2 class="login-title mt-4">Kode Verifikasi</h2>

            <!-- DESCRIPTION -->
            <p class="text-muted">Masukan Kode Verifikasi yang <br>kami kirimkan ke Email Anda</p>

            <!-- FORM -->
            <form action="#" method="POST">
                @csrf

                <!-- EMAIL / PHONE -->
                <div class="mb-3 text-start">
                    <label class="form-label">Kode Verifikasi</label>
                    <input id="code" type="text" class="form-control" placeholder="Masukan Kode Verifikasi Anda">
                </div>

                <div class="text-muted text-center">
                    Belum menerima kode?
                    <a href="#" id="resend-code" class="text-primary fw-normal text-decoration-none">
                        Kirim Kode
                    </a>
                </div>

                <!-- BUTTON -->
                <a href="{{ route('register-password') }}" id="btn-next"
                    class="btn btn-login w-100 text-white d-flex align-items-center justify-content-center mt-4 mb-5"
                    data-btn-target="code">
                    BERIKUTNYA
                </a>
            </form>

        </div>
    </div>

    <script>
        document.getElementById('btn-next')
            .addEventListener('click', async function(e) {

                e.preventDefault();

                const otp = document.getElementById('code').value.trim();

                // ambil email dari localStorage
                const email = localStorage.getItem('register_email');

                if (!email) {
                    alert('Email tidak ditemukan');
                    return;
                }

                // validasi OTP
                if (!otp) {
                    alert('Kode OTP wajib diisi');
                    return;
                }

                if (otp.length !== 6) {
                    alert('Kode OTP harus 6 digit');
                    return;
                }

                try {

                    const res = await fetch('/api/otp/verify', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            purpose: 'register',
                            email: email,
                            otp: otp
                        })
                    });

                    const result = await res.json();

                    console.log(result);

                    // gagal
                    if (!res.ok) {

                        if (result.errors?.otp) {
                            alert(result.errors.otp[0]);
                            return;
                        }

                        alert(result.message || 'OTP tidak valid');
                        return;
                    }

                    // simpan register token
                    const token =
                        result?.data?.register_token ||
                        result?.register_token ||
                        null;

                    if (!token) {
                        alert('Token dari server tidak valid');
                        return;
                    }

                    localStorage.setItem('register_token', token);
                    // redirect halaman password
                    window.location.href = '/register';

                } catch (err) {

                    console.error(err);

                    alert('Terjadi kesalahan server');
                }

            });
    </script>
    <script>
        document.getElementById('resend-code')
            .addEventListener('click', async function(e) {

                e.preventDefault();

                const email = localStorage.getItem('register_email');

                if (!email) {
                    alert('Email tidak ditemukan');
                    return;
                }

                try {

                    const btn = this;

                    btn.innerText = 'Mengirim...';
                    btn.style.pointerEvents = 'none';

                    const res = await fetch('/api/otp/request', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            purpose: 'register',
                            email: email
                        })
                    });

                    const result = await res.json();

                    console.log(result);

                    // ✔ FIX DI SINI (setelah result ada)
                    const token =
                        result?.data?.register_token ||
                        result?.register_token;

                    if (token) {
                        localStorage.setItem('register_token', token);
                    }

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
@endsection
