@extends('auth.auth')

@section('title', 'Daftar')

@section('auth')
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
                    <a href="#" class="forgot-link">Kirim Kode</a>
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
                    localStorage.setItem(
                        'register_token',
                        result.data.register_token
                    );

                    // redirect halaman password
                    window.location.href = '/register';

                } catch (err) {

                    console.error(err);

                    alert('Terjadi kesalahan server');
                }

            });
    </script>
@endsection
