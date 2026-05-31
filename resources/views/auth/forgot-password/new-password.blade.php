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
            <p class="text-muted">Masukan Kata Sandi Baru Anda untuk Masuk ke dalam <br>Akun Toko Beras Jampang</p>

            <!-- FORM -->
            <form action="#" method="POST">
                @csrf

                <!-- PASSWORD -->
                <div class="mb-3 text-start">
                    <label class="form-label">Kata Sandi</label>

                    <div class="password-wrapper">
                        <input type="password" name="password" class="form-control"
                            placeholder="Masukan Kata Sandi Baru Anda" id="password">
                        <div class="invalid-feedback"></div>

                        <span class="password-toggle" data-target="password">
                            <span class="iconify" data-icon="weui:eyes-off-outlined"></span>
                        </span>

                    </div>
                </div>

                <!-- KONFIRMASI PASSWORD -->
                <div class="mb-3 text-start">
                    <label class="form-label">Konfirmasi Kata Sandi</label>

                    <div class="password-wrapper">
                        <input type="password" name="password_confirmation" class="form-control"
                            placeholder="Masukan Konfirmasi Kata Sandi Baru Anda" id="password_confirmation">
                        <div class="invalid-feedback"></div>

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
            document.addEventListener('DOMContentLoaded', function() {

                const adjustIconPosition = (input) => {
                    const icon = input.parentNode.querySelector('.password-toggle');
                    if (!icon) return;

                    icon.style.marginTop = input.classList.contains('is-invalid') ?
                        '-14px' :
                        '0';
                };

                document.getElementById('btn-next').addEventListener('click', async function(e) {
                    e.preventDefault();

                    const passwordInput = document.getElementById('password');
                    const confirmInput = document.getElementById('password_confirmation');

                    const password = passwordInput.value.trim();
                    const passwordConfirmation = confirmInput.value.trim();

                    const email = localStorage.getItem('reset_email');
                    const resetToken = localStorage.getItem('reset_token');

                    const passwordFeedback = passwordInput.parentNode.querySelector('.invalid-feedback');
                    const confirmFeedback = confirmInput.parentNode.querySelector('.invalid-feedback');

                    // RESET ERROR
                    passwordInput.classList.remove('is-invalid');
                    confirmInput.classList.remove('is-invalid');

                    passwordFeedback.textContent = '';
                    confirmFeedback.textContent = '';

                    adjustIconPosition(passwordInput);
                    adjustIconPosition(confirmInput);

                    // VALIDASI
                    if (!password) {
                        passwordInput.classList.add('is-invalid');
                        passwordFeedback.textContent = 'Password wajib diisi';
                        adjustIconPosition(passwordInput);
                        return;
                    }

                    if (!passwordConfirmation) {
                        confirmInput.classList.add('is-invalid');
                        confirmFeedback.textContent = 'Konfirmasi password wajib diisi';
                        adjustIconPosition(confirmInput);
                        return;
                    }

                    if (password.length < 8) {
                        passwordInput.classList.add('is-invalid');
                        passwordFeedback.textContent = 'Password minimal 8 karakter';
                        adjustIconPosition(passwordInput);
                        return;
                    }

                    if (password !== passwordConfirmation) {
                        confirmInput.classList.add('is-invalid');
                        confirmFeedback.textContent = 'Konfirmasi password tidak cocok';
                        adjustIconPosition(confirmInput);
                        return;
                    }

                    if (!email || !resetToken) {
                        confirmInput.classList.add('is-invalid');
                        confirmFeedback.textContent = 'Session expired, redirecting...';

                        setTimeout(() => {
                            window.location.href = '/login';
                        }, 1500);

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

                        // ERROR RESPONSE
                        if (!res.ok) {

                            if (result.errors) {

                                if (result.errors.password) {
                                    passwordInput.classList.add('is-invalid');
                                    passwordFeedback.textContent = result.errors.password[0];
                                    adjustIconPosition(passwordInput);
                                }

                                if (result.errors.password_confirmation) {
                                    confirmInput.classList.add('is-invalid');
                                    confirmFeedback.textContent = result.errors.password_confirmation[0];
                                    adjustIconPosition(confirmInput);
                                }

                            } else {
                                confirmInput.classList.add('is-invalid');
                                confirmFeedback.textContent = result.message || 'Gagal reset password';
                                adjustIconPosition(confirmInput);
                            }

                            return;
                        }

                        // SUCCESS
                        localStorage.removeItem('reset_email');
                        localStorage.removeItem('reset_token');

                        alert('Password berhasil diubah');
                        window.location.href = '/login';

                    } catch (err) {
                        console.error(err);

                        confirmInput.classList.add('is-invalid');
                        confirmFeedback.textContent = 'Terjadi kesalahan server';
                        adjustIconPosition(confirmInput);
                    }
                });

            });
        </script>

    @endsection
