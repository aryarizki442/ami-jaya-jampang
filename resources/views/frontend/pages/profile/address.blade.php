@extends('frontend.pages.profile.account')

@section('title', 'Alamat Saya')

@section('account-content')

    <style>
        .myAddress {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
        }

        .profile-header {
            border-top: 2px solid #e5e5e5;
            margin-top: 20px;
        }

        .address-card {
            padding: 18px 0;
        }

        .divider {
            width: 1.5px;
            height: 22px;
            background: #000;
        }

        .address-card a {
            text-decoration: none;
            font-size: 14px;
        }

        .address-card .btn-success {
            background: #1F7D53;
            border: none;
        }

        .ubah-link {
            color: #57A5E8;
            text-decoration: none;
        }

        .ubah-link:hover {
            text-decoration: underline;
        }
    </style>

    <section class="myAddress">
        <div class="d-flex align-items-center justify-content-between">
            <h5 class="mt-3">Alamat Saya</h5>
            <button class="btn btn-success btn-sm px-3 d-flex align-items-center gap-2" data-bs-toggle="modal"
                data-bs-target="#alamatModal">
                <span class="iconify" data-icon="ic:round-plus"></span>
                Tambah Alamat Baru
            </button>
        </div>

        <div class="address-card profile-header">
            <div class="d-flex justify-content-between align-items-start">

                <div>
                    <div class="d-flex align-items-center mb-2">
                        <strong class="me-3 fw-bold">Malik</strong>

                        <span class="divider"></span>

                        <span class="text-muted ms-3">08129845232</span>
                    </div>

                    <p class="text-muted mb-2">
                        Perumahan Cibinong Rt111 Rw222 Cibinong, Kabupaten Bogor,
                        Jawa Barat 16345
                    </p>

                    <div class="d-flex align-items-center gap-2 text-success">
                        <span class="iconify" data-icon="ic:baseline-house"></span>
                        <span>Rumah</span>
                    </div>
                </div>

                <div class="text-end">
                    <div class="mt-4 d-flex justify-content-end gap-3">
                        <a href="#" class="ubah-link me-3">Ubah</a>
                        <a href="#" class="ubah-link">Hapus</a>
                    </div>

                    <button class="btn btn-success btn-sm px-4 mt-2">
                        Alamat Utama
                    </button>
                </div>

            </div>
        </div>
    </section>

    <div class="modal fade" id="alamatModal" tabindex="-1">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">

                <!-- HEADER -->
                <div class="modal-header">
                    <h5 class="modal-title fw-semibold">Alamat Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <!-- BODY -->
                <div class="modal-body">

                    <!-- Nama & Telepon -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nomor Telepon</label>
                            <input type="text" class="form-control">
                        </div>
                    </div>

                    <!-- Wilayah -->
                    <div class="mb-3">
                        <label class="form-label">Provinsi</label>
                        <input type="text" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kota/Kabupaten</label>
                        <input type="text" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kecamatan</label>
                        <input type="text" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kelurahan/Desa</label>
                        <input type="text" class="form-control">
                    </div>

                    <!-- Detail -->
                    <div class="mb-3">
                        <label class="form-label">Detail Lainnya</label>
                        <textarea class="form-control" rows="3"></textarea>
                    </div>

                    <!-- Tandai Sebagai -->
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mt-4">

                        <!-- Kiri -->
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-outline-secondary btn-sm">
                                Rumah
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm">
                                Kantor
                            </button>
                        </div>

                        <!-- Kanan -->
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-light btn-outline-success btn-sm" data-bs-dismiss="modal">
                                Nanti Saja
                            </button>
                            <button type="button" class="btn btn-success btn-sm">
                                Simpan
                            </button>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
@endsection
