@extends('auth.auth')

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
            <p class="text-muted">Masukan Email anda yang sudah terdaftar, kemudian <br>
                ikuti langkah pada Email yang kami kirimkan</p>

            <!-- FORM -->
            <form action="#" method="POST">
                @csrf

                <!-- EMAIL / PHONE -->
                <div class="mb-3 text-start">
                    <label class="form-label">Email</label>
                    <input id="email" type="text" class="form-control" placeholder="Masukan Email">
                </div>

                <!-- BUTTON -->
                <a href="{{ route('verification') }}" id="btn-next"
                    class="btn btn-login w-100 text-white d-flex align-items-center justify-content-center mb-5 mt-3"
                    data-btn-target="email">
                    BERIKUTNYA
                </a>

            </form>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

@endsection
