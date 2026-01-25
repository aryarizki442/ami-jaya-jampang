@extends('app')

@section('title', 'Login')

@section('content')
    <div class="login-page">
        <div class="login-card text-center">

            <!-- LOGO -->
            <div class="login-logo">
                <img src="{{ asset('images/logo/logo-hijau.png') }}" alt="Logo">
            </div>

            <!-- TITLE -->
            <h2 class="login-title mt-4">Selamat Datang</h2>

            <!-- FORM -->
            <form action="#" method="POST">
                @csrf

                <!-- EMAIL / PHONE -->
                <div class="mb-3 text-start">
                    <label class="form-label">Email/No. Telepon</label>
                    <input type="text" class="form-control" placeholder="Masukan Email/Nomor Telepon Anda">
                </div>

                <!-- PASSWORD -->
                <div class="mb-2 text-start">
                    <label class="form-label">Kata Sandi</label>

                    <div class="password-wrapper">
                        <input type="password" class="form-control" placeholder="Masukan Kata Sandi Anda" id="password">

                        <i class="bi bi-eye-slash password-toggle" id="togglePassword"></i>
                    </div>
                </div>


                <!-- FORGOT -->
                <div class="text-end mb-4">
                    <a href="#" class="forgot-link">Lupa Kata Sandi?</a>
                </div>

                <!-- BUTTON -->
                <button type="submit" class="btn btn-login w-100 text-white">
                    MASUK
                </button>
            </form>

            <!-- REGISTER -->
            <div class="mt-4 register-text">
                Belum Punya Akun?
                <a href="#">Daftar</a>
            </div>

        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');

        togglePassword.addEventListener('click', function() {
            const isPassword = passwordInput.type === 'password';

            passwordInput.type = isPassword ? 'text' : 'password';

            this.classList.toggle('bi-eye');
            this.classList.toggle('bi-eye-slash');
        });
    </script>


@endsection
