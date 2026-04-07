@extends('auth.auth')

@section('title', 'Daftar')

@section('auth')
    <div class="login-page">
        <div class="login-card text-center">

            <!-- LOGO -->
            <div class="login-logo">
                <img src="{{ asset('images/logo/logo-hijau.png') }}" alt="Logo">
            </div>

            <!-- TITLE -->
            <h2 class="login-title mt-4">Daftar</h2>

            <!-- FORM -->
            <form action="#" method="POST">
                @csrf

                <!-- Nama -->
                <div class="mb-3 text-start">
                    <label class="form-label">Nama</label>
                    <input id="name" type="text" name="name" class="form-control"
                        placeholder="Masukan Nama Anda">
                    <div class="invalid-feedback"></div>
                </div>


                <!-- Email -->
                <div class="mb-3 text-start">
                    <label class="form-label">Email</label>
                    <input id="email" type="email" name="email" class="form-control"
                        placeholder="Masukan Email Anda">
                    <div class="invalid-feedback"></div>
                </div>

                <!-- Telepon -->
                <div class="mb-3 text-start">
                    <label class="form-label">No Telepon</label>
                    <input id="phone" type="text" name="phone" class="form-control"
                        placeholder="Masukan Nomor Telepon Anda">
                    <div class="invalid-feedback"></div>
                </div>

                <!-- BUTTON -->
                <a href="{{ route('register-password') }}" id="btn-next" class="btn btn-login w-100 text-white mt-4"
                    data-btn-target="name,email,phone">
                    BERIKUTNYA
                </a>
            </form>

            <!-- REGISTER -->
            <div class="mt-4 register-text">
                Sudah Punya Akun?
                <a href="{{ route('login') }}">Masuk</a>

            </div>

        </div>
    </div>
@endsection
