@extends('backend.app')

@section('title', 'Pengaturan')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        .settings-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 24px;
        }

        .nav-tabs {
            border-bottom: 1px solid #dee2e6;
        }

        .nav-tabs .nav-link {
            color: #6c757d;
            border: none;
            font-size: 14px;
            padding: 12px 20px;
        }

        .nav-tabs .nav-link.active {
            color: #198754;
            border: none;
            border-bottom: 2px solid #198754;
            background: transparent;
            font-weight: 500;
        }

        .section-title {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #212529;
        }

        .form-label {
            color: #6c757d;
            font-size: 14px;
            margin-bottom: 8px;
        }

        .form-control {
            border-radius: 4px;
            min-height: 42px;
        }

        textarea.form-control {
            min-height: 110px;
            resize: none;
        }

        .btn-save {
            background: #198754;
            border: none;
            min-width: 100px;
        }

        .btn-save:hover {
            background: #157347;
        }

        .upload-box {
            position: relative;
            border: 1px dashed #cfd8e3;
            border-radius: 6px;
            height: 300px;
            overflow: hidden;
            background: #fff;
        }

        .upload-content {
            width: 100%;
            height: 100%;
            position: relative;
        }


        .upload-content img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }


        /* overlay default tersembunyi */
        .image-overlay {
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.45);

            display: flex;
            justify-content: center;
            align-items: center;

            opacity: 0;
            visibility: hidden;

            transition: all .3s ease;
        }


        /* muncul ketika hover gambar */
        .upload-content:hover .image-overlay {
            opacity: 1;
            visibility: visible;
        }


        .btn-change-image {
            background: #fff;
            color: #198754;
            border: none;

            padding: 8px 18px;
            border-radius: 5px;

            font-weight: 600;
            cursor: pointer;
        }


        .btn-change-image:hover {
            background: #198754;
            color: white;
        }

        .upload-icon {
            font-size: 38px;
            color: #198754;
            display: block;
            margin-bottom: 12px;
        }

        .upload-content small {
            display: block;
            font-size: 13px;
        }

        .btn-save {
            min-width: 90px;
        }
    </style>

    <div class="container-fluid">
        <div class="settings-card">
            <ul class="nav nav-tabs mb-4" id="settingTab" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#general" type="button">
                        Pengaturan Umum
                    </button>
                </li>

                <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#carousel" type="button">
                        Carousel
                    </button>
                </li>
            </ul>

            <div class="tab-content">
                {{-- Pengaturan Umum --}}
                <div class="tab-pane fade show active" id="general">

                    <div class="section-title">
                        Detail Toko
                    </div>

                    <form>
                        <div class="mb-3">
                            <label class="form-label">Nama Toko</label>
                            <input type="text" name="store_name" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Alamat</label>
                            <textarea name="address" class="form-control">
                            </textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">No Telepon</label>
                            <input type="text" name="phone" class="form-control">
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control">
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-main btn-save">
                                Simpan
                            </button>
                        </div>
                    </form>

                </div>

                {{-- Carousel --}}
                <div class="tab-pane fade" id="carousel">

                    <div class="section-title mb-4">
                        Detail Gambar
                    </div>

                    <form enctype="multipart/form-data">

                        {{-- Carousel 1 --}}
                        <div class="mb-4 ">
                            <label class="form-label">Gambar Carousel 1</label>

                            <div class="upload-box ">
                                <div class="upload-content">
                                    <i class="fas fa-image upload-icon"></i>

                                    <input type="file" id="carousel1" name="carousel_1" hidden>
                                    <button type="button" class="btn btn-image-admin fw-medium mb-2"
                                        onclick="document.getElementById('carousel1').click()">
                                        Masukan Gambar
                                    </button>

                                    <small class="text-muted mt-3">
                                        Seret dan taruh gambar
                                    </small>
                                </div>
                            </div>
                        </div>

                        {{-- Carousel 2 --}}
                        <div class="mb-4">
                            <label class="form-label">Gambar Carousel 2</label>

                            <div class="upload-box">
                                <div class="upload-content">
                                    <i class="fas fa-image upload-icon"></i>

                                    <input type="file" id="carousel2" name="carousel_2" hidden>


                                    <button type="button" class="btn btn-image-admin fw-medium mb-2"
                                        onclick="document.getElementById('carousel2').click()">
                                        Masukan Gambar
                                    </button>
                                    <small class="text-muted mt-3">
                                        Seret dan taruh gambar
                                    </small>
                                </div>
                            </div>
                        </div>

                        {{-- Carousel 3 --}}
                        <div class="mb-4">
                            <label class="form-label">Gambar Carousel 3</label>

                            <div class="upload-box">
                                <div class="upload-content">
                                    <i class="fas fa-image upload-icon"></i>

                                    <input type="file" id="carousel3" name="carousel_3" hidden>

                                    <button type="button" class="btn btn-image-admin fw-medium mb-2"
                                        onclick="document.getElementById('carousel3').click()">
                                        Masukan Gambar
                                    </button>

                                    <small class="text-muted mt-3">
                                        Seret dan taruh gambar
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-main btn-save">
                                Simpan
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>

    <script>
        const API_URL = "/api/settings";

        // ==========================
        // LOAD DATA SETTING
        // ==========================
        async function loadSetting() {
            try {
                const response = await fetch(API_URL);

                const result = await response.json();

                if (result.success && result.data) {

                    const data = result.data;

                    // General
                    document.querySelector('[name="store_name"]').value = data.store_name ?? '';
                    document.querySelector('[name="address"]').value = data.address ?? '';
                    document.querySelector('[name="phone"]').value = data.phone ?? '';
                    document.querySelector('[name="email"]').value = data.email ?? '';
                    // Carousel preview
                    if (data.carousel_1) {
                        showPreview(
                            'carousel1',
                            data.carousel_1
                        );
                    }


                    if (data.carousel_2) {
                        showPreview(
                            'carousel2',
                            data.carousel_2
                        );
                    }


                    if (data.carousel_3) {
                        showPreview(
                            'carousel3',
                            data.carousel_3
                        );
                    }


                }

            } catch (error) {
                console.error(error);
                alert('Gagal mengambil data setting');
            }
        }


        // ==========================
        // UPDATE GENERAL
        // ==========================
        document.querySelector('#general form')
            .addEventListener('submit', async function(e) {

                e.preventDefault();

                const data = {
                    store_name: document.querySelector('[name="store_name"]').value,
                    address: document.querySelector('[name="address"]').value,
                    phone: document.querySelector('[name="phone"]').value,
                    email: document.querySelector('[name="email"]').value,
                };


                try {

                    const response = await fetch(
                        API_URL + "/general", {
                            method: "PUT",
                            headers: {
                                "Content-Type": "application/json",
                                "Accept": "application/json",
                                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify(data)
                        }
                    );


                    const result = await response.json();


                    if (result.success) {
                        alert(result.message);
                        loadSetting();
                    }

                } catch (error) {
                    console.error(error);
                    alert("Gagal menyimpan pengaturan");
                }

            });



        // ==========================
        // UPDATE CAROUSEL
        // ==========================
        document.querySelector('#carousel form')
            .addEventListener('submit', async function(e) {

                e.preventDefault();


                let formData = new FormData();


                const carousel1 = document.querySelector('#carousel1');
                const carousel2 = document.querySelector('#carousel2');
                const carousel3 = document.querySelector('#carousel3');


                if (carousel1.files[0]) {
                    formData.append(
                        'carousel_1',
                        carousel1.files[0]
                    );
                }


                if (carousel2.files[0]) {
                    formData.append(
                        'carousel_2',
                        carousel2.files[0]
                    );
                }


                if (carousel3.files[0]) {
                    formData.append(
                        'carousel_3',
                        carousel3.files[0]
                    );
                }



                try {

                    const response = await fetch(
                        API_URL + "/carousel", {
                            method: "POST",
                            headers: {
                                "Accept": "application/json",
                                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: formData
                        }
                    );


                    const result = await response.json();


                    if (result.success) {
                        alert(result.message);
                        loadSetting();
                    }

                } catch (error) {

                    console.error(error);
                    alert("Upload carousel gagal");

                }

            });

        function showPreview(inputId, imagePath) {

            const input = document.getElementById(inputId);

            const box = input.closest('.upload-box');


            box.querySelector('.upload-content').innerHTML = `

        <img
            src="/storage/${imagePath}?t=${Date.now()}"
        >


        <div class="image-overlay">

            <input
                type="file"
                id="${inputId}"
                name="${inputId.replace('carousel','carousel_')}"
                hidden
            >


            <button
                type="button"
                class="btn-change-image"
                onclick="document.getElementById('${inputId}').click()"
            >
                Ganti Gambar
            </button>

        </div>

    `;


            document
                .getElementById(inputId)
                .addEventListener('change', function() {
                    previewLocalImage(this);
                });

        }

        function previewLocalImage(input) {

            const file = input.files[0];

            if (!file) return;


            const reader = new FileReader();


            reader.onload = function(e) {

                const box = input.closest('.upload-box');


                box.querySelector('.upload-content').innerHTML = `

        <img
            src="${e.target.result}"
        >


        <div class="image-overlay">

            <input
                type="file"
                id="${input.id}"
                name="${input.name}"
                hidden
            >


            <button
                type="button"
                class="btn-change-image"
                onclick="document.getElementById('${input.id}').click()"
            >
                Ganti Gambar
            </button>

        </div>

    `;


                const newInput = box.querySelector(`#${input.id}`);


                const dataTransfer = new DataTransfer();

                dataTransfer.items.add(file);

                newInput.files = dataTransfer.files;


                newInput.addEventListener('change', function() {
                    previewLocalImage(this);
                });

            };


            reader.readAsDataURL(file);
        }

        // LOAD SAAT HALAMAN DIBUKA
        document.addEventListener(
            "DOMContentLoaded",
            loadSetting
        );
    </script>
@endsection
