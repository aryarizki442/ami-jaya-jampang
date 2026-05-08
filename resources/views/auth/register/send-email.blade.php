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
            <h2 class="login-title mt-4">Verifikasi Email</h2>

            <!-- DESCRIPTION -->
            <p class="text-muted">Masukan Email aktif anda, kemudian ikuti <br>langkah pada Email Yang kami kirimkan</p>

            <!-- FORM -->
            <form action="#" method="POST">
                @csrf

                <!-- EMAIL / PHONE -->
                <div class="mb-3 text-start">
                    <label class="form-label">Email</label>
                    <input id="email" type="text" class="form-control"
                        placeholder="Masukan Email/Nomor Telepon Anda">
                </div>

                <!-- BUTTON -->
                <a href="{{ route('send-otp') }}" id="btn-next"
                    class="btn btn-login w-100 text-white d-flex align-items-center justify-content-center mb-5 mt-5"
                    data-btn-target="email">
                    BERIKUTNYA
                </a>

            </form>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('btn-next')
            .addEventListener('click', async function(e) {

                e.preventDefault();

                const email = document.getElementById('email').value.trim();

                // validasi kosong
                if (!email) {
                    alert('Email wajib diisi');
                    return;
                }

                try {

                    const res = await fetch('/api/otp/request', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            purpose: 'register',
                            email: email
                        })
                    });

                    const result = await res.json();

                    console.log(result);

                    // gagal
                    if (!res.ok) {

                        // tampilkan error validasi
                        if (result.errors?.email) {
                            alert(result.errors.email[0]);
                            return;
                        }

                        alert(result.message || 'Gagal mengirim OTP');
                        return;
                    }

                    // simpan email sementara
                    localStorage.setItem('register_email', email);

                    // redirect ke halaman OTP
                    window.location.href = "{{ route('send-otp') }}";

                } catch (err) {

                    console.error(err);

                    alert('Terjadi kesalahan server');
                }

            });
    </script>
@endsection
