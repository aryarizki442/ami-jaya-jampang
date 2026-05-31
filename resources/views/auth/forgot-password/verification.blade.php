@extends('auth.auth')

@section('title', 'Verifikasi')

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
            <p class="text-muted">Masukkan Kode Verifikasi yang kami <br> kirimkan
                ke alamat email anda.</p>

            <!-- FORM -->
            <form action="#" method="POST">
                @csrf

                <!-- EMAIL / PHONE -->
                <div class="mb-3 text-start">
                    <label class="form-label">Kode Verifikasi</label>
                    <input id="code" type="text" class="form-control" placeholder="Masukan Kode Verifikasi Anda">
                    <div class="invalid-feedback"></div>
                </div>

                <div class="text-muted text-center">
                    Belum menerima kode?
                    <a href="#" id="resend-code" class="text-primary fw-normal text-decoration-none">
                        Kirim Kode
                    </a>
                </div>

                <!-- BUTTON -->
                <a href="{{ route('new-password') }}" id="btn-next"
                    class="btn btn-login w-100 text-white d-flex align-items-center justify-content-center mt-4 mb-5"
                    data-btn-target="code">
                    BERIKUTNYA
                </a>
            </form>

        </div>
    </div>



    <script>
        document.getElementById('btn-next').addEventListener('click', async function(e) {
            e.preventDefault();

            const otp = document.getElementById('code').value.trim();
            const email = localStorage.getItem('forgot_email');

            const otpInput = document.getElementById('code');
            const otpFeedback = otpInput.parentNode.querySelector('.invalid-feedback');

            otpInput.classList.remove('is-invalid');
            otpFeedback.textContent = '';
            if (!otp) {
                otpInput.classList.add('is-invalid');
                otpFeedback.textContent = 'Kode OTP wajib diisi';
                return;
            }

            if (!email) {
                alert('Email tidak ditemukan, ulangi dari awal');
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
                        purpose: 'forgot_password',
                        email: email,
                        otp: otp
                    })
                });

                const result = await res.json();


                if (!res.ok) {
                    otpInput.classList.add('is-invalid');

                    if (result.errors?.otp) {
                        otpFeedback.textContent = result.errors.otp[0];
                    } else {
                        otpFeedback.textContent = result.message || 'Kode OTP tidak valid';
                    }

                    return;
                }

                // 🔥 SIMPAN SESSION RESET
                localStorage.setItem('reset_email', result.data.email);
                localStorage.setItem('reset_token', result.data.reset_token);
                // lanjut ke halaman password baru
                window.location.href = "{{ route('new-password') }}";

            } catch (err) {
                console.error(err);
                alert('Server error');
            }
        });
    </script>
    <script>
        document.getElementById('resend-code')
            .addEventListener('click', async function(e) {

                e.preventDefault();

                const email = localStorage.getItem('forgot_email');

                const otpInput = document.getElementById('code');
                const otpFeedback = otpInput.parentNode.querySelector('.invalid-feedback');

                const btn = this;

                // RESET UI ERROR
                otpInput.classList.remove('is-invalid');
                otpFeedback.textContent = '';

                if (!email) {
                    otpInput.classList.add('is-invalid');
                    otpFeedback.textContent = 'Email tidak ditemukan';
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
                            purpose: 'forgot_password',
                            email: email
                        })
                    });

                    const result = await res.json();

                    console.log(result);

                    if (!res.ok) {
                        otpInput.classList.add('is-invalid');
                        otpFeedback.textContent = result.message || 'Gagal kirim ulang OTP';
                        return;
                    }

                    btn.innerText = 'Kode terkirim ulang';
                    btn.style.color = 'green';

                    alert('Kode OTP berhasil dikirim ulang');

                } catch (err) {

                    console.error(err);

                    alert('Server error');

                } finally {

                    const btn = document.getElementById('resend-code');

                    btn.innerText = 'Kirim Kode';
                    btn.style.pointerEvents = 'auto';
                }

            });
    </script>
@endsection
