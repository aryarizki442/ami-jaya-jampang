@extends('frontend.pages.profile.account')

@section('title', 'Profil Saya')

@section('account-content')

    <style>
        .myProfile {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
        }

        .profile-header {
            border-bottom: 2px solid #e5e5e5;
            padding-bottom: 18px;
            margin-bottom: 25px;
        }

        .profile-divider {
            position: relative;
        }

        .profile-divider::before {
            content: "";
            position: absolute;
            left: 0;
            top: 40%;
            transform: translateY(-50%);
            width: 2px;
            height: 250px;
            background: #e5e5e5;
        }

        .profile-divider img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            box-shadow: none;
        }


        .profile-value {
            width: 220px;
            /* mengatur posisi kolom teks */
        }

        .profile-value-wrapper {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .upload-info {
            font-size: 16px;
            color: #888;
        }

        .btn-save {
            background-color: #198754;
            color: white;
            padding: 4px 15px;
            border-radius: 6px;
        }

        .btn-save:hover {
            background-color: #000;
        }

        .ubah-link {
            color: #57A5E8;
            text-decoration: none;
        }

        .ubah-link:hover {
            text-decoration: underline;
        }

        .form-check-input:checked {
            background-color: #fff;
            border-color: #10B500;
            background-image: none;
        }

        .form-check-input:checked::before {
            content: "";
            display: block;
            width: 8px;
            height: 8px;
            margin: 3px auto;
            border-radius: 50%;
            background-color: #10B500;
        }

        .text-placeholder {
            color: #B8B9BA;
        }

        .date-group {
            display: flex;
            gap: 12px;
        }

        .date-group .select-wrapper {
            flex: 1;
            /* semua kolom sama besar */
        }

        .date-group .select-wrapper select {
            width: 100%;
        }

        .select-wrapper {
            position: relative;
            display: inline-flex;
            align-items: center;
        }

        .select-wrapper select {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: none !important;

            padding-right: 40px;
            /* ruang icon */
            color: #B8B9BA;
        }

        .select-wrapper select.form-select {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: none !important;
        }

        .select-wrapper iconify-icon {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 16px;
            color: #B8B9BA;
            pointer-events: none;
        }

        .select-wrapper select:has(option:checked[value=""]) {
            color: #B8B9BA;
        }

        .select-wrapper select:not(:has(option:checked[value=""])) {
            color: #000;
        }

        .select-wrapper select option {
            color: #000;
        }

        /* =========================
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        /* tablet */
        @media (max-width: 992px) {
            .profile-value {
                width: 160px;
            }

            .profile-divider img {
                width: 100px;
                height: 100px;
            }

            .profile-divider::before {
                height: 130px;
            }

        }



        /* mobile */
        @media (max-width: 768px) {
            .profile-divider::before {
                display: none;
            }

            .profile-divider {
                margin-bottom: 25px;
            }

            .profile-divider img {
                width: 90px;
                height: 90px;
            }

            /* label di atas */
            .row.align-items-center {
                flex-direction: column;
                align-items: flex-start !important;
                gap: 5px;
            }

            .row.align-items-center label {
                text-align: left !important;
                padding-right: 0 !important;
            }

            /* isi value + ubah tetap sejajar */
            .row.align-items-center .col-sm {
                display: flex;
                align-items: center;
                justify-content: space-between;
                width: 100%;
            }

            .profile-value {
                width: auto;
            }

            .ubah-link {
                font-size: 12px;
                margin-left: 10px;
            }

            .select-wrapper {
                width: 100%;
            }

            .select-wrapper select {
                width: 100%;
                padding-right: 40px;
                font-size: 9px;

            }

            .select-wrapper iconify-icon {
                right: 14px;

            }
        }


        /* mobile kecil */
        @media (max-width: 576px) {

            .col-sm-9.d-flex {
                flex-direction: column;
                gap: 10px;
            }

            .link-response {
                margin-left: -1px;
            }

            .select-wrapper {
                width: 100%;
            }

            .select-wrapper select {
                width: 100%;
            }

            .btn-save {
                width: 100%;
            }

        }
    </style>
    <section class="myProfile mt-5 mb-5">
        <div class="profile-header">
            <h5 class="mb-1">Profil Saya</h5>
            <small style="color: #B8B9BA">
                Kelola Informasi profil Anda untuk mengontrol, melindungi, dan mengamankan akun
            </small>
        </div>

        <div class="row">

            {{-- FORM KIRI --}}
            <div class="col-md-8">

                {{-- Nama --}}
                <div class="row align-items-center mb-4">
                    <label class="col-sm-3 col-form-label text-end pe-4" style="color: #B8B9BA">
                        Nama
                    </label>

                    <div class="col-sm d-flex align-items-center gap-2">

                        <!-- TEXT MODE -->
                        <p class="mb-0 profile-value" id="profileName">-</p>

                        <!-- EDIT MODE (hidden awalnya) -->
                        <input type="text" id="editName" class="form-control form-control-sm d-none"
                            style="max-width:200px;">

                        <a href="#" id="btnEditName" class="ubah-link small">Ubah Nama</a>
                        <a href="#" id="btnSaveName" class="ubah-link small d-none">Simpan</a>

                    </div>
                </div>

                {{-- Email --}}
                <div class="row align-items-center mb-4">
                    <label class="col-sm-3 col-form-label  text-end pe-4" style="color: #B8B9BA">
                        Email
                    </label>

                    <div class="col-sm d-flex align-items-center">
                        <p class="mb-0 profile-value"id="profileEmail">-</p>
                        <a href="{{ route('verify-email', ['target' => 'email']) }}" class="ubah-link small">
                            Ubah Email
                        </a>
                    </div>
                </div>

                {{-- No Telepon --}}
                <div class="row align-items-center mb-4">
                    <label class="col-sm-3 col-form-label text-end pe-4" style="color: #B8B9BA">
                        No.Telepon
                    </label>

                    <div class="col-sm d-flex align-items-center">
                        <p class="mb-0 profile-value" id="profilePhone">-</p>
                        <a href="{{ route('verify-email', ['target' => 'phone']) }}" class="ubah-link small">
                            Ubah No Telepon
                        </a>
                    </div>
                </div>

                <div class="row align-items-center profile-row mb-4">
                    <label class="col-sm-3 col-form-label text-end pe-4" style="color: #B8B9BA">
                        Kata Sandi
                    </label>

                    <div class="col-sm d-flex align-items-center">
                        <a href="{{ route('verify-email', ['target' => 'password']) }}"
                            class="ubah-link small link-response">
                            Ubah Kata Sandi
                        </a>
                    </div>
                </div>

                {{-- Jenis Kelamin --}}
                <div class="row align-items-center mb-4">
                    <label class="col-sm-3 col-form-label text-end pe-4" style="color: #B8B9BA">
                        Jenis Kelamin
                    </label>

                    <div class="col-sm-9">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="jenis_kelamin" id="laki"
                                value="male">
                            <label class="form-check-label" for="laki">Laki Laki</label>
                        </div>

                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="jenis_kelamin" id="perempuan"
                                value="female">
                            <label class="form-check-label" for="perempuan">Perempuan</label>
                        </div>
                    </div>
                </div>
                {{-- Tanggal Lahir --}}
                <div class="row align-items-center mb-4">
                    <label class="col-sm-3 col-form-label text-end pe-4" style="color: #B8B9BA">
                        Tanggal Lahir
                    </label>
                    <div class="col-sm-9 date-group">

                        <p id="profileBirthText" class="mb-0 profile-value"></p>

                        <div id="birthSelects" class="d-none d-flex gap-2">

                            <div class="select-wrapper">
                                <select class="form-select" id="birthDay">
                                    <option selected disabled style="color: #B8B9BA" value="">Tanggal</option>
                                    @for ($i = 1; $i <= 31; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                                <iconify-icon icon="iconamoon:arrow-down-2-light"></iconify-icon>
                            </div>

                            {{-- BULAN --}}
                            <div class="select-wrapper">
                                <select class="form-select" id="birthMonth">
                                    <option selected disabled style="color: #B8B9BA" value="">Bulan</option>
                                    <option value="1">Januari</option>
                                    <option value="2">Februari</option>
                                    <option value="3">Maret</option>
                                    <option value="4">April</option>
                                    <option value="5">Mei</option>
                                    <option value="6">Juni</option>
                                    <option value="7">Juli</option>
                                    <option value="8">Agustus</option>
                                    <option value="9">September</option>
                                    <option value="10">Oktober</option>
                                    <option value="11">November</option>
                                    <option value="12">Desember</option>
                                </select>
                                <iconify-icon icon="iconamoon:arrow-down-2-light"></iconify-icon>
                            </div>

                            {{-- TAHUN --}}
                            <div class="select-wrapper">
                                <select class="form-select" id="birthYear">
                                    <option selected disabled value="">Tahun</option>
                                    @for ($i = date('Y'); $i >= 1950; $i--)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                                <iconify-icon icon="iconamoon:arrow-down-2-light"></iconify-icon>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            {{-- FOTO KANAN --}}
            <div class="col-md-4 profile-divider text-center mt-4 pt-4">

                <!-- Preview Avatar -->
                <img id="profileAvatarForm" src="{{ asset('images/home/user/user-group.png') }}"
                    class="rounded-circle mb-3 w-40" alt="Foto Profil">

                <!-- Hidden Input -->
                <input type="file" id="avatarInput" accept="image/png, image/jpeg" hidden>

                <div>
                    <button type="button" class="btn btn-main" id="chooseImageBtn">
                        Pilih Gambar
                    </button>
                </div>

                <div class="upload-info" style="color: #B8B9BA">
                    Ukuran Gambar: maks. 1 MB <br>
                    Format Gambar: JPG, PNG
                </div>

            </div>

            <div class="col-sm-9 offset-sm-2 mt-2">
                <button class="btn btn-main" id="btnSaveProfile">
                    Simpan
                </button>
            </div>

        </div>
    </section>
    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>

    <script>
        /* =========================
                                                                                                                                                                       UTILS
                                                                                                                                                                    ========================= */
        function formatBirthDate(dateString) {
            if (!dateString) return '-';
            const [y, m, d] = dateString.split('-');
            const months = [
                'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
            ];
            return `${parseInt(d)} ${months[parseInt(m) - 1]} ${y}`;
        }

        function getToken() {
            return localStorage.getItem('token');
        }

        async function apiFetch(url, options = {}) {
            const token = getToken();
            const headers = {
                'Accept': 'application/json',
                'Authorization': 'Bearer ' + token,
                ...(options.headers || {})
            };

            // Jangan set Content-Type jika FormData (biar browser yg handle boundary)
            if (!(options.body instanceof FormData)) {
                headers['Content-Type'] = 'application/json';
            }

            const res = await fetch(url, {
                ...options,
                headers
            });
            const data = await res.json();
            if (!res.ok) throw data;
            return data;
        }

        function avatarUrl(path) {
            if (!path) return DEFAULT_AVATAR;
            if (path.startsWith('http')) return path;
            if (path.startsWith('/storage/')) return window.location.origin + path;
            return window.location.origin + '/storage/' + path;
        }

        /* =========================
           LOAD PROFILE
        ========================= */
        async function loadProfile() {
            try {
                const res = await apiFetch('/api/me');
                const user = res.data?.user || res.data;

                document.getElementById('profileName').textContent = user.name ?? '-';
                document.getElementById('profileEmail').textContent = user.email ?? '-';
                document.getElementById('profilePhone').textContent = user.phone ?? '-';

                if (user?.email) sessionStorage.setItem('profile_email', user.email);
                if (user?.phone) sessionStorage.setItem('profile_phone', user.phone);

                // Avatar di form profil
                const avatarForm = document.getElementById('profileAvatarForm');
                if (avatarForm) avatarForm.src = avatarUrl(user.avatar);

                // Avatar di sidebar
                const avatarSidebar = document.getElementById('profileAvatar');
                if (avatarSidebar) avatarSidebar.src = avatarUrl(user.avatar);

                // Gender
                if (user.gender) {
                    const radio = document.querySelector(`input[name="jenis_kelamin"][value="${user.gender}"]`);
                    if (radio) radio.checked = true;
                }

                // Birth date selects
                if (user.birth_date) {
                    const [year, month, day] = user.birth_date.split('-');
                    const birthDay = document.getElementById('birthDay');
                    const birthMonth = document.getElementById('birthMonth');
                    const birthYear = document.getElementById('birthYear');
                    if (birthDay) birthDay.value = Number(day);
                    if (birthMonth) birthMonth.value = Number(month);
                    if (birthYear) birthYear.value = Number(year);
                }

                // Birth date text vs selects
                const birthText = document.getElementById('profileBirthText');
                const birthSelects = document.getElementById('birthSelects');
                if (user.birth_date) {
                    birthText.textContent = formatBirthDate(user.birth_date);
                    birthText.classList.remove('d-none');
                    birthSelects.classList.add('d-none');
                } else {
                    birthText.textContent = '-';
                    birthText.classList.add('d-none');
                    birthSelects.classList.remove('d-none');
                }

                // Update navbar/sidebar jika ada helper setUser
                if (typeof setUser === 'function') {
                    setUser({
                        name: user.name ?? 'User',
                        avatar: avatarUrl(user.avatar), // ← kirim URL final, bukan raw path
                        email: user.email ?? null
                    });
                }

            } catch (err) {
                console.error('loadProfile error:', err);
            }
        }
        /* =========================
           INLINE EDIT NAME
        ========================= */
        document.addEventListener('DOMContentLoaded', function() {

            const nameText = document.getElementById('profileName');
            const editInput = document.getElementById('editName');
            const btnEdit = document.getElementById('btnEditName');
            const btnSave = document.getElementById('btnSaveProfile');
            const avatarInput = document.getElementById('avatarInput');
            const profileAvatar = document.getElementById('profileAvatarForm');
            const chooseImageBtn = document.getElementById('chooseImageBtn');

            // Simpan preview sementara (belum diupload)
            window.selectedAvatar = null;

            /* --- Tombol Edit Nama --- */
            if (btnEdit) {
                btnEdit.addEventListener('click', function(e) {
                    e.preventDefault();
                    editInput.value = nameText.textContent;
                    nameText.classList.add('d-none');
                    editInput.classList.remove('d-none');
                    btnEdit.classList.add('d-none');
                    btnSave.classList.remove('d-none');

                    // Tampilkan selects birth date saat mode edit
                    document.getElementById('profileBirthText').classList.add('d-none');
                    document.getElementById('birthSelects').classList.remove('d-none');
                });
            }

            /* --- Pilih Gambar → Preview Saja, Belum Upload --- */
            if (chooseImageBtn) {
                chooseImageBtn.addEventListener('click', function() {
                    avatarInput.click();
                });
            }

            if (avatarInput) {
                avatarInput.addEventListener('change', function() {
                    const file = this.files[0];
                    if (!file) return;

                    if (file.size > 1024 * 1024) {
                        alert('Ukuran gambar maksimal 1 MB');
                        avatarInput.value = '';
                        return;
                    }

                    if (!['image/jpeg', 'image/jpg', 'image/png'].includes(file.type)) {
                        alert('Format harus JPG atau PNG');
                        avatarInput.value = '';
                        return;
                    }

                    // Preview langsung di form profil
                    profileAvatar.src = URL.createObjectURL(file);
                    window.selectedAvatar = file;
                });
            }

            /* --- Tombol Simpan --- */
            if (btnSave) {
                btnSave.addEventListener('click', async function(e) {
                    e.preventDefault();

                    try {
                        // Name
                        const name = editInput?.value || nameText?.textContent;

                        // Gender
                        const gender = document.querySelector('input[name="jenis_kelamin"]:checked')
                            ?.value;

                        // Birth date
                        const tanggal = document.getElementById('birthDay')?.value;
                        const bulan = document.getElementById('birthMonth')?.value;
                        const tahun = document.getElementById('birthYear')?.value;

                        let birth_date = null;
                        if (tanggal && bulan && tahun) {
                            birth_date =
                                `${tahun}-${String(bulan).padStart(2, '0')}-${String(tanggal).padStart(2, '0')}`;
                        }

                        // 1. Update profil (nama, gender, birth_date)
                        const res = await apiFetch('/api/profile', {
                            method: 'PUT',
                            body: JSON.stringify({
                                name,
                                gender,
                                birth_date
                            })
                        });

                        // 2. Upload avatar jika ada yang dipilih
                        if (window.selectedAvatar) {
                            const formData = new FormData();
                            formData.append('avatar', window.selectedAvatar);

                            const avatarRes = await apiFetch('/api/avatar', {
                                method: 'POST',
                                body: formData
                            });

                            // Update avatar dari response server (URL final)
                            if (avatarRes.data?.avatar) {
                                const finalUrl = avatarUrl(avatarRes.data.avatar);

                                // Update avatar di form profil
                                const avatarForm = document.getElementById('profileAvatarForm');
                                if (avatarForm) avatarForm.src = finalUrl;

                                // Update avatar di sidebar
                                const avatarSidebar = document.getElementById('profileAvatar');
                                if (avatarSidebar) avatarSidebar.src = finalUrl;
                            }
                        }

                        alert(res.message || 'Profil berhasil diperbarui');

                        // Reset state
                        window.selectedAvatar = null;
                        avatarInput.value = '';

                        // Reload profil (sekaligus update navbar/sidebar via setUser di loadProfile)
                        await loadProfile();

                        // Kembalikan UI ke mode tampil
                        nameText.classList.remove('d-none');
                        editInput.classList.add('d-none');
                        btnEdit?.classList.remove('d-none');
                        btnSave.classList.add('d-none');

                    } catch (err) {
                        console.error('saveProfile error:', err);
                        alert(err.message || 'Gagal menyimpan profil');
                    }
                });
            }

            // Load profil saat halaman siap
            loadProfile();
        });
    </script>
@endsection
