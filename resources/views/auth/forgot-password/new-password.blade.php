@extends('auth.auth')

@section('title', 'Kata Sandi Baru')

@section('auth')
    <div class="login-page">
        <div class="login-card text-center pb-4">

            <!-- LOGO -->
            <div class="login-logo">
                <img src="{{ asset('images/logo/logo-hijau.png') }}" alt="Logo">
            </div>

            <!-- TITLE -->
            <h2 class="login-title mt-4">Kata Sandi Baru</h2>
            <p class="text-muted">Masukan Kata Sandi Baru Anda untuk Masuk ke dalam <br>Akun Toko Online Ami Jaya Jampang</p>

            <!-- FORM -->
            <form action="#" method="POST">
                @csrf

                <!-- PASSWORD -->
                <div class="mb-3 text-start">
                    <label class="form-label">Kata Sandi</label>

                    <div class="password-wrapper">
                        <input type="password" name="password" class="form-control"
                            placeholder="Masukan Kata Sandi Baru Anda" id="password">

                        <span class="password-toggle" data-target="password">
                            <span class="iconify" data-icon="weui:eyes-off-outlinedd"></span>
                        </span>

                    </div>
                </div>

                <!-- KONFIRMASI PASSWORD -->
                <div class="mb-3 text-start">
                    <label class="form-label">Konfirmasi Kata Sandi</label>

                    <div class="password-wrapper">
                        <input type="password" name="password_confirmation" class="form-control"
                            placeholder="Masukan Konfirmasi Kata Sandi Baru Anda" id="password_confirmation">

                        <span class="password-toggle" data-target="password_confirmation">
                            <span class="iconify" data-icon="weui:eyes-off-outlined"></span>
                    </div>
                </div>

                <!-- BUTTON -->
                <a href="{{ route('login') }}" id="btn-next"
                    class="btn btn-login w-100 text-white d-flex align-items-center justify-content-center mb-5"\
                    data-btn-target="password,password_confirmation">
                    SELESAI
                </a>
            </form>
        </div>
        <script>
            document.getElementById('btn-next').addEventListener('click', async function(e) {
                e.preventDefault();

                const password = document.getElementById('password').value.trim();
                const passwordConfirmation = document.getElementById('password_confirmation').value.trim();

                const email = localStorage.getItem('reset_email');
                const resetToken = localStorage.getItem('reset_token');

                console.log('reset_email:', localStorage.getItem('reset_email'));
                console.log('reset_token:', localStorage.getItem('reset_token'));

                // validasi frontend
                if (!password || !passwordConfirmation) {
                    alert('Password wajib diisi');
                    return;
                }

                if (password.length < 8) {
                    alert('Password minimal 8 karakter');
                    return;
                }

                if (password !== passwordConfirmation) {
                    alert('Konfirmasi password tidak cocok');
                    return;
                }

                if (!email || !resetToken) {
                    alert('Session reset tidak ditemukan, ulangi dari awal');
                    return;
                }

                try {
                    const res = await fetch('/api/forgot-password/reset', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            email: email,
                            reset_token: resetToken,
                            password: password,
                            password_confirmation: passwordConfirmation
                        })
                    });

                    const result = await res.json();

                    console.log(result);

                    // gagal
                    if (!res.ok) {
                        alert(result.message || 'Gagal reset password');
                        return;
                    }

                    // bersihkan storage
                    localStorage.removeItem('reset_email');
                    localStorage.removeItem('reset_token');

                    // redirect ke login
                    alert('Password berhasil diubah');
                    window.location.href = '/login';

                } catch (err) {
                    console.error(err);
                    alert('Server error');
                }
            });
        </script>

    @endsection
