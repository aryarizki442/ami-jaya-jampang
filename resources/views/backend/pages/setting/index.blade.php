@extends('backend.app')

@section('title', 'Pengaturan')

@section('content')
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
            border: 1px dashed #cfd8e3;
            border-radius: 4px;
            min-height: 155px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fff;
        }

        .upload-content {
            text-align: center;
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
                            <input type="text" class="form-control" value="Ami Jaya Jampang">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Alamat</label>
                            <textarea class="form-control">Jl. Raya Parung, Desa Jampang Kec. Kemang Kabupaten Bogor</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">No Telepon</label>
                            <input type="text" class="form-control" value="081122334455">
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" value="amijayajampang@gmail.com">
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

                            <div class="upload-box py-5">
                                <div class="upload-content">
                                    <i class="fas fa-image upload-icon"></i>

                                    <input type="file" id="carousel1" hidden>
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

                            <div class="upload-box py-5">
                                <div class="upload-content">
                                    <i class="fas fa-image upload-icon"></i>

                                    <input type="file" id="carousel2" hidden>


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

                            <div class="upload-box py-5">
                                <div class="upload-content">
                                    <i class="fas fa-image upload-icon"></i>

                                    <input type="file" id="carousel3" hidden>

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
@endsection
