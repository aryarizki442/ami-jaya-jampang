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

    <script>
        // auto isi email dari OTP sebelumnya
        document.getElementById('email').value =
            localStorage.getItem('register_email') || '';

        document.getElementById('btn-next')
            .addEventListener('click', function(e) {

                e.preventDefault();

                const name = document.getElementById('name').value.trim();
                const email = document.getElementById('email').value.trim();
                const phone = document.getElementById('phone').value.trim();
                const nameInput = document.getElementById('name');
                const emailInput = document.getElementById('email');
                const phoneInput = document.getElementById('phone');

                const nameFeedback = nameInput.parentNode.querySelector('.invalid-feedback');
                const emailFeedback = emailInput.parentNode.querySelector('.invalid-feedback');
                const phoneFeedback = phoneInput.parentNode.querySelector('.invalid-feedback');

                nameInput.classList.remove('is-invalid');
                emailInput.classList.remove('is-invalid');
                phoneInput.classList.remove('is-invalid');

                nameFeedback.textContent = '';
                emailFeedback.textContent = '';
                phoneFeedback.textContent = '';

                // validasi
                if (!name || !email || !phone) {

                    if (!name) {
                        nameInput.classList.add('is-invalid');
                        nameFeedback.textContent = 'Nama wajib diisi';
                    }

                    if (!email) {
                        emailInput.classList.add('is-invalid');
                        emailFeedback.textContent = 'Email wajib diisi';
                    }

                    if (!phone) {
                        phoneInput.classList.add('is-invalid');
                        phoneFeedback.textContent = 'Nomor telepon wajib diisi';
                    }

                    return;
                }

                // simpan data
                localStorage.setItem('register_name', name);
                localStorage.setItem('register_phone', phone);

                // email tetap update
                localStorage.setItem('register_email', email);

                // pindah halaman
                window.location.href =
                    "{{ route('register-password') }}";

            });
    </script>
@endsection
