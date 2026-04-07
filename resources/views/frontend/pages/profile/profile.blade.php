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
                                                                                                                                                                                                                                   RESPONSIVE PROFILE IMPROVED
                                                                                                                                                                                                                                ========================= */

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
    <section class="myProfile">
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

                    <div class="col-sm d-flex align-items-center">
                        <p class="mb-0 profile-value">malikhassan123</p>
                        <a href="#" class="ubah-link small">Ubah Nama</a>
                    </div>
                </div>

                {{-- Email --}}
                <div class="row align-items-center mb-4">
                    <label class="col-sm-3 col-form-label  text-end pe-4" style="color: #B8B9BA">
                        Email
                    </label>

                    <div class="col-sm d-flex align-items-center">
                        <p class="mb-0 profile-value">ma******@gmail.com</p>
                        <a href="#" class="ubah-link small">Ubah Email</a>
                    </div>
                </div>

                {{-- No Telepon --}}
                <div class="row align-items-center mb-4">
                    <label class="col-sm-3 col-form-label text-end pe-4" style="color: #B8B9BA">
                        No.Telepon
                    </label>

                    <div class="col-sm d-flex align-items-center">
                        <p class="mb-0 profile-value">08********66</p>
                        <a href="#" class="ubah-link small">Ubah No.Telepon</a>
                    </div>
                </div>

                <div class="row align-items-center mb-4">
                    <label class="col-sm-3 col-form-label text-end pe-4" style="color: #B8B9BA">
                        Kata Sandi
                    </label>

                    <div class="col-sm d-flex align-items-center">
                        <a href="#" class="ubah-link small">Ubah Kata Sandi</a>
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
                                value="laki" checked>
                            <label class="form-check-label" for="laki">Laki Laki</label>
                        </div>

                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="jenis_kelamin" id="perempuan"
                                value="perempuan">
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

                        {{-- TANGGAL --}}
                        <div class="select-wrapper">
                            <select class="form-select ">
                                <option selected disabled style="color: #B8B9BA" value="">Tanggal</option>
                                @for ($i = 1; $i <= 31; $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                            <iconify-icon icon="iconamoon:arrow-down-2-light"></iconify-icon>
                        </div>

                        {{-- BULAN --}}
                        <div class="select-wrapper">
                            <select class="form-select">
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
                            <select class="form-select">
                                <option selected disabled style="color: #B8B9BA" value="">Tahun</option>
                                @for ($i = date('Y'); $i >= 1950; $i--)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                            <iconify-icon icon="iconamoon:arrow-down-2-light"></iconify-icon>
                        </div>

                    </div>
                </div>



            </div>

            {{-- FOTO KANAN --}}
            <div class="col-md-4 profile-divider text-center mt-4 pt-4">

                <img src="{{ asset('images/home/category/beras-medium.png') }}" class="rounded-circle mb-3 w-50"
                    alt="Foto Profil">

                <div>
                    <button class="btn btn-save">Pilih Gambar</button>
                </div>

                <div class="upload-info" style="color: #B8B9BA">
                    Ukuran Gambar: maks. 1 MB <br>
                    Format Gambar: JPG, PNG
                </div>

            </div>

            <div class="col-sm-9 offset-sm-2 mt-2">
                <button class="btn btn-save">Simpan</button>
            </div>

        </div>
    </section>
    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>

@endsection
