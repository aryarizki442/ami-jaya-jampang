@extends('frontend.auth.auth')

@section('title', 'Lupa Kata Sandi')

@section('auth')
    <div class="login-page">
        <div class="login-card text-center">

            <!-- LOGO -->
            <div class="login-logo">
                <img src="{{ asset('images/logo/logo-hijau.png') }}" alt="Logo">
            </div>

            <!-- TITLE -->
            <h2 class="login-title mt-4">Lupa Kata Sandi</h2>

            <!-- DESCRIPTION -->
            <p class="text-muted">Masukan Email dan No.Telepon <br>anda yang sudah terdaftar
                kemudian ikuti <br>langkah pada Email dan No.Telepon yang
                kami kirimkan</p>

            <!-- FORM -->
            <form action="#" method="POST">
                @csrf

                <!-- EMAIL / PHONE -->
                <div class="mb-3 text-start">
                    <label class="form-label">Email/No. Telepon</label>
                    <input type="text" class="form-control" placeholder="Masukan Email/Nomor Telepon Anda">
                </div>

                <!-- BUTTON -->
                <a href="{{ route('verification') }}"
                    class="btn btn-login w-100 text-white d-flex align-items-center justify-content-center mb-5 mt-5">
                    BERIKUTNYA
                </a>

            </form>

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
