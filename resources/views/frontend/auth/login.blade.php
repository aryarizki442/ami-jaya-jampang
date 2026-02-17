@extends('frontend.auth.auth')

@section('title', 'Login')

@section('auth')
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
                    <label class="form-label text-black">Email/No. Telepon</label>
                    <input type="text" class="form-control" placeholder="Masukan Email/Nomor Telepon Anda">
                </div>

                <!-- PASSWORD -->
                <div class="mb-2 text-start">
                    <label class="form-label text-black">Kata Sandi</label>

                    <div class="password-wrapper">
                        <input type="password" class="form-control" placeholder="Masukan Kata Sandi Anda" id="password">

                        <span class="password-toggle" data-target="password">
                            <span class="iconify" data-icon="weui:eyes-on-filled"></span>
                        </span>
                    </div>
                </div>


                <!-- FORGOT -->
                <div class="text-end mb-4 forgot-password">
                    <a href="{{ route('forgot-password') }}" class="forgot-link ">Lupa Kata Sandi?</a>
                </div>

                <!-- BUTTON -->
                <a href="{{ route('/home') }}"
                    class="btn btn-login w-100 text-white d-flex align-items-center justify-content-center">
                    MASUK
                </a>

            </form>

            <!-- REGISTER -->
            <div class="mt-4 register-text">
                Belum Punya Akun?
                <a href="{{ route('register') }}">Daftar</a>
            </div>

        </div>
    </div>

    <!-- Bootstrap JS -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

@endsection
