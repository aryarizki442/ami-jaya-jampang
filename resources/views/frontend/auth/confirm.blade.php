@extends('app')

@section('title', 'Confirm Registration')

@section('content')
    <div class="login-page">
        <div class="login-card text-center">

            <!-- LOGO -->
            <div class="login-logo">
                <img src="{{ asset('images/logo/logo-hijau.png') }}" alt="Logo">
            </div>

            <!-- TITLE -->
            <h2 class="login-title mt-4">Konfirmasi Password</h2>

            <!-- FORM -->
            <form action="#" method="POST">
                @csrf

                <!-- PASSWORD -->
                <div class="mb-3 text-start">
                    <label class="form-label">Kata Sandi</label>

                    <div class="password-wrapper">
                        <input type="password" name="password" class="form-control" placeholder="Masukan Kata Sandi"
                            id="password">

                        <i class="bi bi-eye-slash password-toggle" id="togglePassword"></i>
                    </div>
                </div>

                <!-- KONFIRMASI PASSWORD -->
                <div class="mb-3 text-start">
                    <label class="form-label">Konfirmasi Kata Sandi</label>

                    <div class="password-wrapper">
                        <input type="password" name="password_confirmation" class="form-control"
                            placeholder="Ulangi Kata Sandi" id="password_confirmation">

                        <i class="bi bi-eye-slash password-toggle" id="toggleConfirmPassword"></i>
                    </div>
                </div>

                <!-- BUTTON -->
                <button type="submit" class="btn btn-login w-100 text-white">
                    SELESAI
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
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);

            const isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';

            icon.classList.toggle('bi-eye');
            icon.classList.toggle('bi-eye-slash');
        }

        document.getElementById('togglePassword')
            .addEventListener('click', () =>
                togglePassword('password', 'togglePassword')
            );

        document.getElementById('toggleConfirmPassword')
            .addEventListener('click', () =>
                togglePassword('password_confirmation', 'toggleConfirmPassword')
            );
    </script>



@endsection
