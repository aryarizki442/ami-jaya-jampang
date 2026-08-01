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
                    <div class="invalid-feedback"></div>
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

    <!-- MODAL CEK EMAIL -->
    <div class="modal fade" id="checkEmailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0">

                <div class="modal-body text-center p-4">

                    <h4 class="fw-bold mt-3">
                        OTP Berhasil Dikirim
                    </h4>

                    <p class="text-muted">
                        Silakan cek email Anda sekarang untuk mendapatkan kode OTP.
                    </p>

                    <p class="small text-muted">
                        Kode OTP dikirim ke:
                        <br>
                        <strong id="modal-email"></strong>
                    </p>

                    <button type="button" id="btn-to-otp" class="btn btn-login w-100 text-white mt-3">
                        MASUKKAN KODE OTP
                    </button>

                </div>

            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('btn-next')
            .addEventListener('click', async function(e) {

                e.preventDefault();

                const email = document.getElementById('email').value.trim();
                const emailInput = document.getElementById('email');
                const emailFeedback = emailInput.parentNode.querySelector('.invalid-feedback');

                // validasi kosong
                if (!email) {
                    emailInput.classList.add('is-invalid');
                    emailFeedback.textContent = 'Email wajib diisi';
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


                    // gagal
                    if (!res.ok) {

                        // tampilkan error validasi
                        if (result.errors?.email) {
                            emailInput.classList.add('is-invalid');
                            emailFeedback.textContent = result.errors.email[0];
                            return;
                        }

                        alert(result.message || 'Gagal mengirim OTP');
                        return;
                    }

                    // simpan email sementara
                    localStorage.setItem('register_email', email);

                    // redirect ke halaman OTP
                    // tampilkan email pada modal
                    document.getElementById('modal-email').textContent = email;

                    // tampilkan modal cek email
                    const checkEmailModal = new bootstrap.Modal(
                        document.getElementById('checkEmailModal')
                    );

                    checkEmailModal.show();

                } catch (err) {

                    console.error(err);

                    alert('Terjadi kesalahan server');
                }

            });

        document.getElementById('btn-to-otp')
            .addEventListener('click', function() {

                window.location.href = "{{ route('send-otp') }}";

            });
    </script>
@endsection
