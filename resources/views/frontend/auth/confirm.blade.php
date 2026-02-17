@extends('frontend.auth.auth')

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
                            <span class="iconify" data-icon="weui:eyes-on-filled"></span>
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
                            <span class="iconify" data-icon="weui:eyes-on-filled"></span>
                    </div>
                </div>

                <!-- BUTTON -->
                <a href="{{ route('login') }}"
                    class="btn btn-login w-100 text-white d-flex align-items-center justify-content-center mb-5">
                    SELESAI
                </a>
            </form>
        </div>

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


    @endsection
