@extends('auth.auth')

@section('title', 'Daftar Akun')

@section('auth')
    <div class="login-page">
        <div class="login-card text-center pb-4">

            <!-- LOGO -->
            <div class="login-logo">
                <img src="{{ asset('images/logo/logo-hijau.png') }}" alt="Logo">
            </div>

            <!-- TITLE -->
            <h2 class="login-title mt-4">Daftar</h2>

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
                            <span class="iconify" data-icon="weui:eyes-off-outlined"></span>
                        </span>
                        <div class="invalid-feedback"></div> <!-- di luar password-wrapper -->
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
                        </span>
                    </div>
                </div>

                <!-- BUTTON -->
                <button type="button" id="btn-next"
                    class="btn btn-login w-100 text-white d-flex align-items-center justify-content-center mb-3"
                    data-btn-target="password,password_confirmation">
                    SELESAI
                </button>
            </form>
            <div class="mt-2 mb-4 register-text">
                Sudah Punya Akun?
                <a href="{{ route('login') }}">Masuk</a>

            </div>
        </div>
    </div>

    <!-- Modal Bootstrap -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-center p-4">
                <h5 class="modal-title mb-3">Pembuatan Akun Berhasil </h5>
                <p>Anda akan diarahkan ke halaman login dalam 3 detik...</p>
            </div>
        </div>
    </div>
@endsection
