@extends('auth.auth')

@section('title', 'Daftar')

@section('auth')
    <style>
        .send-code {
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            color: var(--info-button-700);
        }

        .send-code:hover {
            color: var(--info-button-500);
        }

        /* MODAL NOTIFIKASI */
        #notificationModal .modal-content {
            border-radius: 20px;
        }

        .modal-icon {
            width: 65px;
            height: 65px;

            display: flex;
            align-items: center;
            justify-content: center;

            margin: auto;

            border-radius: 50%;

            font-size: 32px;
            font-weight: bold;

            color: white;
            background-color: #dc3545;
        }

        .modal-icon.success {
            background-color: #198754;
        }

        .modal-icon.error {
            background-color: #dc3545;
        }

        .modal-icon.warning {
            background-color: #f0ad4e;
        }
    </style>
    <div class="login-page">
        <div class="login-card text-center">

            <!-- LOGO -->
            <div class="login-logo">
                <img src="{{ asset('images/logo/logo-hijau.png') }}" alt="Logo">
            </div>

            <!-- TITLE -->
            <h2 class="login-title mt-4">Kode Verifikasi</h2>

            <!-- DESCRIPTION -->
            <p class="text-muted">Masukan Kode Verifikasi yang <br>kami kirimkan ke Email Anda</p>

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
                    <a href="#" id="resend-code" class="text-primary fw-normal text-decoration-none">
                        Kirim Kode
                    </a>
                </div>

                <!-- BUTTON -->
                <a href="{{ route('register-password') }}" id="btn-next"
                    class="btn btn-login w-100 text-white d-flex align-items-center justify-content-center mt-4 mb-5"
                    data-btn-target="code">
                    BERIKUTNYA
                </a>
            </form>

        </div>
    </div>
    <!-- MODAL NOTIFIKASI -->
    <div class="modal fade" id="notificationModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">

                <div class="modal-body text-center p-4">

                    <div id="modal-icon" class="modal-icon">
                        !
                    </div>

                    <h4 id="modal-title" class="fw-bold mt-3">
                        Informasi
                    </h4>

                    <p id="modal-message" class="text-muted mb-4">
                        Pesan notifikasi
                    </p>

                    <button type="button" class="btn btn-login w-100 text-white" data-bs-dismiss="modal">
                        MENGERTI
                    </button>

                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function showNotification(
            message,
            type = 'error',
            title = 'Terjadi Kesalahan'
        ) {

            const modalElement =
                document.getElementById('notificationModal');

            const modal =
                new bootstrap.Modal(modalElement);

            const icon =
                document.getElementById('modal-icon');

            document.getElementById('modal-title')
                .textContent = title;

            document.getElementById('modal-message')
                .textContent = message;

            icon.className = 'modal-icon ' + type;

            if (type === 'success') {
                icon.innerHTML = '✓';
            } else if (type === 'warning') {
                icon.innerHTML = '!';
            } else {
                icon.innerHTML = '×';
            }

            modal.show();
        }
    </script>
    <script>
        document.getElementById('btn-next')
            .addEventListener('click', async function(e) {

                e.preventDefault();

                const otp = document.getElementById('code').value.trim();

                // ambil email dari localStorage
                const email = localStorage.getItem('register_email');

                if (!email) {
                    showNotification(
                        'Email tidak ditemukan. Silakan kembali dan masukkan email Anda.',
                        'error',
                        'Email Tidak Ditemukan'
                    );
                    return;
                }

                // validasi OTP
                if (!otp) {
                    showNotification(
                        'Kode verifikasi wajib diisi.',
                        'warning',
                        'Kode Belum Diisi'
                    );
                    return;
                }

                if (otp.length !== 6) {
                    showNotification(
                        'Kode verifikasi harus terdiri dari 6 digit.',
                        'warning',
                        'Kode Tidak Valid'
                    );
                    return;
                }

                try {

                    const res = await fetch('/api/otp/verify', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            purpose: 'register',
                            email: email,
                            otp: otp
                        })
                    });

                    const result = await res.json();


                    // gagal
                    if (!res.ok) {

                        if (result.errors?.otp) {
                            showNotification(
                                result.errors.otp[0],
                                'error',
                                'Kode Tidak Valid'
                            );
                            return;
                        }

                        showNotification(
                            result.message || 'Kode OTP tidak valid.',
                            'error',
                            'Verifikasi Gagal'
                        );
                        return;
                    }

                    // simpan register token
                    const token =
                        result?.data?.register_token ||
                        result?.register_token ||
                        null;

                    if (!token) {
                        showNotification(
                            'Token dari server tidak valid.',
                            'error',
                            'Terjadi Kesalahan'
                        );
                        return;
                    }

                    localStorage.setItem('register_token', token);
                    // redirect halaman password
                    window.location.href = '/register';

                } catch (err) {

                    console.error(err);

                    showNotification(
                        'Terjadi kesalahan pada server. Silakan coba lagi.',
                        'error',
                        'Kesalahan Server'
                    );
                }

            });
    </script>
    <script>
        document.getElementById('resend-code')
            .addEventListener('click', async function(e) {

                e.preventDefault();

                const email = localStorage.getItem('register_email');

                if (!email) {
                    alert('Email tidak ditemukan');
                    return;
                }

                try {

                    const btn = this;

                    btn.innerText = 'Mengirim...';
                    btn.style.pointerEvents = 'none';

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


                    // ✔ FIX DI SINI (setelah result ada)
                    const token =
                        result?.data?.register_token ||
                        result?.register_token;

                    if (token) {
                        localStorage.setItem('register_token', token);
                    }

                    if (!res.ok) {
                        showNotification(
                            result.message || 'Gagal mengirim ulang OTP',
                            'error',
                            'Kesalahan'
                        );
                        return;
                    }

                    showNotification(
                        'Kode OTP berhasil dikirim ulang. Silakan cek email Anda.',
                        'success',
                        'Kode Berhasil Dikirim'
                    );

                } catch (err) {

                    console.error(err);
                    showNotification(
                        'Terjadi kesalahan pada server. Silakan coba lagi.',
                        'error',
                        'Kesalahan Server'
                    );
                } finally {

                    const btn = document.getElementById('resend-code');

                    btn.innerText = 'Kirim Kode';
                    btn.style.pointerEvents = 'auto';
                }

            });
    </script>
@endsection
