@extends('frontend.pages.profile.account')

@section('title', 'Semua Pesanan')

@section('account-content')

    <style>
        .order-title {
            background: #2a7b4f;
            color: white;
            text-align: center;
            padding: 10px;
            font-weight: 600;
        }

        .order-search {
            background: #E8E8E9;
            border: 1px solid #e5e5e5;
        }

        .order-card {
            border-radius: 16px;
            border: 1px solid #B8B9BA;
            overflow: hidden;
        }

        .order-card .card-body {
            padding: 30px 24px 24px;
        }

        .order-product {
            flex-wrap: wrap;
        }

        .order-product img {
            width: 70px;
            height: 70px;
            object-fit: cover;
            border-radius: 12px;
        }

        .order-divider {
            border-left: 1px solid #B8B9BA;
        }

        .case {
            background: #fff;
            padding: 20px;
        }

        @media (max-width: 768px) {

            .order-card .card-body {
                padding: 70px 18px 18px;
            }

            .order-product {
                flex-direction: column;
                align-items: flex-start !important;
            }

            .order-divider {
                border-left: 0;
                margin-top: 20px;
                text-align: left !important;
            }

            .status-process {
                padding: 8px 14px;
                font-size: 11px;
            }

        }
    </style>


    <div class="order-title mb-3 mt-4">
        Semua Pesanan
    </div>


    <div class="order-search p-2 rounded mb-3 d-flex align-items-center py-0">
        <span class="iconify me-2" data-icon="majesticons:search-line"></span>
        <input type="text" class="form-control border-0 bg-transparent" placeholder="Cari semua pesanan anda disini">
    </div>


    <div class="case">
        {{-- <div class="card order-card mb-3 position-relative">

            <!-- Badge -->
            <span class="badge status-waiting text-white position-absolute top-0 end-0">
                Menunggu Pembayaran
            </span>

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center mb-0">

                    <div class="order-meta gap-3 d-flex align-items-center text-neutral-custom">
                        Bayar Sebelum
                        <span class="text-warning">
                            <span class="iconify" data-icon="iconoir:clock-solid"></span>
                            27 Jan, 21.00 WIB
                        </span>
                    </div>

                </div>

                <div class="row align-items-center mb-5">

                    <div class="col-md-8">

                        <div class="d-flex align-items-center gap-5 order-product">

                            <img src="{{ asset('images/payments/bank/bca.png') }}" style="width:100px;">
                            <div>
                                <div class="order-meta text-neutral-custom">Metode Pembayaran</div>
                                <strong>BCA Virtual Account</strong>
                            </div>

                            <div>
                                <div class="order-meta text-neutral-custom">Nomor Virtual Account</div>
                                <strong>80773124124121</strong>
                            </div>

                        </div>

                    </div>

                    <div class="col-md-4 order-divider d-flex flex-column justify-content-center text-end">

                        <div class="order-meta text-neutral-custom">Total Pembayaran</div>
                        <strong>Rp.153.000</strong>

                    </div>

                </div>

                <div class="d-flex justify-content-between align-items-center">

                    <div class="d-flex align-items-center gap-2">
                        <strong>Pembelian</strong>
                        <span class="order-meta ms-2 text-neutral-custom">27 Januari 2026</span>
                    </div>

                    <div class="d-flex gap-2">
                        <button class="btn btn-second btn-sm">
                            Cara Pembayaran
                        </button>

                        <button class="btn btn-main btn-sm">
                            Lihat Detail
                        </button>
                    </div>

                </div>

            </div>
        </div>

        <div class="card order-card mb-3 position-relative py-0">
            <div class="d-flex justify-content-between align-items-start">

                <div class="ps-4 pt-2">
                    <strong>Pembelian</strong>
                    <span class="order-meta ms-2 text-neutral-custom">27 Januari 2026</span>
                </div>

                <span class="badge status-process text-white top-0 end-0">
                    Diproses
                </span>

            </div>
            <div class="card-body">

                <div class="row align-items-center mb-5">

                    <div class="col-md-8">

                        <div class="d-flex align-items-center gap-3 order-product">

                            <img src="{{ asset('images/home/category/beras-medium.png') }}" style="width:100px;">

                            <div>
                                <strong>Beras Putih Premium</strong>
                                <div class="order-meta text-neutral-custom">x 1</div>
                            </div>

                        </div>

                    </div>

                    <div class="col-md-4 order-divider d-flex flex-column justify-content-center text-end">

                        <div class="order-meta text-neutral-custom">Total Pembayaran</div>
                        <strong>Rp.153.000</strong>

                    </div>

                </div>

                <div class="d-flex justify-content-end gap-2">

                    <button class="btn btn-second btn-sm">
                        Hubungi Penjual
                    </button>

                </div>

            </div>
        </div>

        <div class="card order-card mb-3 position-relative py-0">
            <div class="d-flex justify-content-between align-items-start">

                <div class="ps-4 pt-2">
                    <strong>Pembelian</strong>
                    <span class="order-meta ms-2 text-neutral-custom">27 Januari 2026</span>
                </div>

                <span class="badge status-shipped text-white top-0 end-0">
                    Dikirim
                </span>

            </div>
            <div class="card-body">

                <div class="row align-items-center mb-5">

                    <div class="col-md-8">

                        <div class="d-flex align-items-center gap-3 order-product">

                            <img src="{{ asset('images/home/category/beras-medium.png') }}" style="width:100px;">

                            <div>
                                <strong>Beras Putih Premium</strong>
                                <div class="order-meta text-neutral-custom">x 1</div>
                            </div>

                        </div>

                    </div>

                    <div class="col-md-4 order-divider d-flex flex-column justify-content-center text-end">

                        <div class="order-meta text-neutral-custom">Total Pembayaran</div>
                        <strong>Rp.153.000</strong>

                    </div>

                </div>

                <div class="d-flex justify-content-end gap-2">

                    <button class="btn btn-second btn-sm">
                        Hubungi Penjual
                    </button>

                    <button class="btn btn-main btn-sm px-3">
                        Selesai
                    </button>

                </div>

            </div>
        </div>

        <div class="card order-card mb-3 position-relative py-0">

            <!-- Badge -->

            <div class="d-flex justify-content-between align-items-start">

                <div class="ps-4 pt-2">
                    <strong>Pembelian</strong>
                    <span class="order-meta ms-2 text-neutral-custom">27 Januari 2026</span>
                </div>

                <span class="badge status-finished text-white top-0 end-0">
                    Selesai
                </span>

            </div>
            <div class="card-body">

                <div class="row align-items-center mb-5">

                    <div class="col-md-8">

                        <div class="d-flex align-items-center gap-3 order-product">

                            <img src="{{ asset('images/home/category/beras-medium.png') }}" style="width:100px;">

                            <div>
                                <strong>Beras Putih Premium</strong>
                                <div class="order-meta text-neutral-custom">x 1</div>
                            </div>

                        </div>

                    </div>

                    <div class="col-md-4 order-divider d-flex flex-column justify-content-center text-end">

                        <div class="order-meta text-neutral-custom">Total Pembayaran</div>
                        <strong>Rp.153.000</strong>

                    </div>

                </div>

                <div class="d-flex justify-content-end gap-2">

                    <button class="btn btn-second btn-sm">
                        Detail Transaksi
                    </button>

                    <button class="btn btn-main btn-sm px-3">
                        Nilai
                    </button>

                </div>

            </div>
        </div>

        <div class="card order-card mb-3 position-relative py-0">

            <!-- Badge -->

            <div class="d-flex justify-content-between align-items-start">

                <div class="ps-4 pt-2">
                    <strong>Pembelian</strong>
                    <span class="order-meta ms-2 text-neutral-custom">27 Januari 2026</span>
                </div>

                <span class="badge status-cancelled text-white top-0 end-0">
                    Dibatalkan
                </span>

            </div>
            <div class="card-body">

                <div class="row align-items-center mb-5">

                    <div class="col-md-8">

                        <div class="d-flex align-items-center gap-3 order-product">

                            <img src="{{ asset('images/home/category/beras-medium.png') }}" style="width:100px;">

                            <div>
                                <strong>Beras Putih Premium</strong>
                                <div class="order-meta text-neutral-custom">x 1</div>
                            </div>

                        </div>

                    </div>

                    <div class="col-md-4 order-divider d-flex flex-column justify-content-center text-end">

                        <div class="order-meta text-neutral-custom">Total Pembayaran</div>
                        <strong>Rp.153.000</strong>

                    </div>

                </div>

                <div class="d-flex justify-content-end gap-2">

                    <button class="btn btn-second btn-sm">
                        Rincian Pembatalan
                    </button>

                    <button class="btn btn-main btn-sm px-3">
                        Beli Lagi
                    </button>

                </div>

            </div>
        </div> --}}
    </div>

    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>

@endsection
