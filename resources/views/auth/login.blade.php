@extends('auth.auth')

@section('title', 'Login')

@section('auth')
    <div class="login-page">
        <div class="login-card text-center">

            <!-- LOGO -->
            <div class="login-logo mb-2">
                <img src="{{ asset('images/logo/logo-hijau.png') }}" alt="Logo">
            </div>

            <!-- TITLE -->
            <h2 class="login-title mt-4">Selamat Datang</h2>

            <!-- FORM -->
            <form id="loginForm">
                @csrf

                <!-- EMAIL / PHONE -->
                <div class="mb-3 text-start">
                    <label class="form-label text-black">Email/No. Telepon</label>
                    <input type="text" class="form-control" name="email_or_phone" id="email_or_phone"
                        placeholder="Masukan Email/Nomor Telepon Anda">
                    <div class="invalid-feedback"></div>
                </div>

                <!-- PASSWORD -->
                <div class="mb-2 text-start">
                    <label class="form-label text-black">Kata Sandi</label>
                    <div class="password-wrapper">
                        <input type="password" class="form-control" name="password" id="password"
                            placeholder="Masukan Kata Sandi Anda">
                        <div class="invalid-feedback"></div>
                        <span class="password-toggle" data-target="password">
                            <span class="iconify" data-icon="weui:eyes-off-outlined"></span>
                        </span>
                    </div>
                </div>

                <!-- FORGOT -->
                <div class="text-end mb-4 forgot-password">
                    <a href="{{ route('forgot-password') }}" class="forgot-link">Lupa Kata Sandi?</a>
                </div>

                <!-- BUTTON -->
                <button type="submit" id="loginBtn"
                    class="btn btn-login w-100 text-white d-flex align-items-center justify-content-center"
                    data-btn-target="email_or_phone,password">
                    MASUK
                </button>
            </form>

            <!-- REGISTER -->
            <div class="mt-3 mb-3 register-text text-muted">
                Belum Punya Akun?
                <a href="{{ route('register') }}">Daftar</a>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('loginForm');

            const adjustIconPosition = (input) => {
                const icon = input.parentNode.querySelector('.password-toggle');
                if (!icon) return;
                icon.style.marginTop = input.classList.contains('is-invalid') ? '-14px' : '0';
            };

            form.addEventListener('submit', async function(e) {
                e.preventDefault();

                const emailInput = form.querySelector('input[name="email_or_phone"]');
                const passwordInput = form.querySelector('input[name="password"]');

                // reset error
                [emailInput, passwordInput].forEach(input => {
                    input.classList.remove('is-invalid');
                    input.nextElementSibling.textContent = '';
                    adjustIconPosition(input);
                });

                const email_or_phone = emailInput.value.trim();
                const password = passwordInput.value.trim();
                let hasError = false;

                // FRONTEND VALIDASI INLINE
                if (!email_or_phone) {
                    emailInput.classList.add('is-invalid');
                    emailInput.nextElementSibling.textContent = 'Email atau nomor telepon wajib diisi';
                    adjustIconPosition(emailInput);
                    hasError = true;
                }
                if (!password) {
                    passwordInput.classList.add('is-invalid');
                    passwordInput.nextElementSibling.textContent = 'Kata sandi wajib diisi';
                    adjustIconPosition(passwordInput);
                    hasError = true;
                }
                if (hasError) return;

                try {
                    const response = await fetch('{{ url('/api/login') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            email_or_phone,
                            password
                        })
                    });

                    const data = await response.json();

                    // backend error inline
                    if (!response.ok && data.errors) {
                        Object.keys(data.errors).forEach(key => {
                            const input = form.querySelector(`[name="${key}"]`);
                            if (input) {
                                input.classList.add('is-invalid');
                                input.nextElementSibling.textContent = data.errors[key][0];
                                adjustIconPosition(input);
                            }
                        });
                    }
                    // sukses
                    else if (response.ok) {
                        localStorage.setItem('access_token', data.data.access_token);
                        window.location.href = "{{ route('/home') }}";
                    }
                    // fallback message
                    else if (data.message) {
                        passwordInput.classList.add('is-invalid');
                        passwordInput.nextElementSibling.textContent = data.message;
                        adjustIconPosition(passwordInput);
                    }

                } catch (err) {
                    console.error(err);
                    passwordInput.classList.add('is-invalid');
                    passwordInput.nextElementSibling.textContent =
                        'Terjadi kesalahan. Silakan coba lagi.';
                    adjustIconPosition(passwordInput);
                }
            });
        });
    </script>
    <script src="{{ asset('js/main.js') }}"></script>

@endsection
