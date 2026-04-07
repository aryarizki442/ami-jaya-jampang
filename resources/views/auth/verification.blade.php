@extends('auth.auth')

@section('title', 'Verifikasi')

@section('auth')
    <div class="login-page">
        <div class="login-card text-center">

            <!-- LOGO -->
            <div class="login-logo">
                <img src="{{ asset('images/logo/logo-hijau.png') }}" alt="Logo">
            </div>

            <!-- TITLE -->
            <h2 class="login-title mt-4">Kode Verifikasi</h2>

            <!-- DESCRIPTION -->
            <p class="text-muted">Masukkan Kode Verifikasi yang kami kirimkan <br>
                ke alamat email atau No.Telepon Anda.</p>

            <!-- FORM -->
            <form action="#" method="POST">
                @csrf

                <!-- EMAIL / PHONE -->
                <div class="mb-3 text-start">
                    <label class="form-label">Kode Verifikasi</label>
                    <input id="code" type="text" class="form-control" placeholder="Masukan Kode Verifikasi Anda">
                </div>

                <div class="text-muted text-center">
                    Belum menerima kode?
                    <a href="#" class="forgot-link">Kirim Kode</a>
                </div>

                <!-- BUTTON -->
                <a href="{{ route('new-password') }}" id="btn-next"
                    class="btn btn-login w-100 text-white d-flex align-items-center justify-content-center mt-4 mb-5"
                    data-btn-target="code">
                    BERIKUTNYA
                </a>
            </form>

        </div>
    </div>
@endsection
