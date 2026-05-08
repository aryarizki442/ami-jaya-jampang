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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('btn-next')
            .addEventListener('click', async function() {

                const password = document.getElementById('password').value.trim();

                const passwordConfirmation =
                    document.getElementById('password_confirmation')
                    .value.trim();

                // validasi
                if (!password || !passwordConfirmation) {
                    alert('Password wajib diisi');
                    return;
                }

                if (password.length < 8) {
                    alert('Password minimal 8 karakter');
                    return;
                }

                if (password !== passwordConfirmation) {
                    alert('Konfirmasi password tidak cocok');
                    return;
                }

                // ambil data dari proses sebelumnya
                const email = localStorage.getItem('register_email');
                const registerToken = localStorage.getItem('register_token');
                const name = localStorage.getItem('register_name');
                const phone = localStorage.getItem('register_phone');

                console.log({
                    email,
                    registerToken,
                    name,
                    phone
                });

                if (!email || !registerToken) {
                    alert('Session registrasi tidak ditemukan');
                    return;
                }

                try {

                    const res = await fetch('/api/auth/register/complete', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            email: email,
                            register_token: registerToken,
                            name: name,
                            phone: phone,
                            password: password,
                            password_confirmation: passwordConfirmation
                        })
                    });

                    const result = await res.json();

                    console.log(result);

                    // gagal
                    if (!res.ok) {

                        if (result.errors) {

                            if (result.errors.password) {
                                alert(result.errors.password[0]);
                                return;
                            }

                            if (result.errors.phone) {
                                alert(result.errors.phone[0]);
                                return;
                            }

                            if (result.errors.name) {
                                alert(result.errors.name[0]);
                                return;
                            }
                        }

                        alert(result.message || 'Registrasi gagal');

                        return;
                    }

                    // simpan token login
                    localStorage.setItem(
                        'token',
                        result.data.access_token
                    );

                    // tampilkan modal sukses
                    const modal = new bootstrap.Modal(
                        document.getElementById('successModal')
                    );

                    modal.show();

                    // hapus temporary register
                    localStorage.removeItem('register_email');
                    localStorage.removeItem('register_token');
                    localStorage.removeItem('register_name');
                    localStorage.removeItem('register_phone');

                    // redirect login / home
                    setTimeout(() => {

                        window.location.href = '/login';

                    }, 3000);

                } catch (err) {

                    console.error(err);

                    alert('Terjadi kesalahan server');
                }

            });
    </script>
@endsection
