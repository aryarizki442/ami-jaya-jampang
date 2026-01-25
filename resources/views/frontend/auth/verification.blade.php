@extends('app')

@section('title', 'Verification')

@section('content')
    <div class="login-page">
        <div class="login-card text-center">

            <!-- LOGO -->
            <div class="login-logo">
                <img src="{{ asset('images/logo/logo-hijau.png') }}" alt="Logo">
            </div>

            <!-- TITLE -->
            <h2 class="login-title mt-4">Kode Verifikasi</h2>

            <!-- DESCRIPTION -->
            <p class="text-muted">Masukkan Kode Verifikasi yang kami kirimkan
                ke alamat email atau No.Telepon Anda.</p>

            <!-- FORM -->
            <form action="#" method="POST">
                @csrf

                <!-- EMAIL / PHONE -->
                <div class="mb-3 text-start">
                    <label class="form-label">Kode Verifikasi</label>
                    <input type="text" class="form-control" placeholder="Masukan Kode Verifikasi Anda">
                </div>

                <!-- BUTTON -->
                <button type="submit" class="btn btn-login w-100 text-white">
                    BERIKUTNYA
                </button>
            </form>


            <!-- REGISTER -->
            <div class="mt-4 register-text">
                Belum Menerima Kode?
                <a href="#">Kirim Kode</a>
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
