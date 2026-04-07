@extends('frontend.pages.profile.account')

@section('title', 'Pesanan Selesai')

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
            border-radius: 8px;
            border: 1px solid #B8B9BA;
        }

        .order-meta {
            font-size: 13px;
            color: #777;
        }

        .order-product img {
            width: 70px;
        }

        .order-divider {
            border-left: 1px solid #B8B9BA;
        }

        .status-wait {
            background: #d8c94a;
        }

        .status-send {
            background: #5aa0e6;
        }

        .status-done {
            background: #00c400;
        }

        .status-cancel {
            background: #ff2b2b;
        }

        .case {
            background: #fff;
            padding: 20px;
        }

        @media (max-width: 768px) {

            .order-card .row {
                flex-direction: column;
                gap: 15px;
            }

            .order-product {
                gap: 10px;
            }

            .order-product div {
                width: 100%;
            }

            .order-product img {
                width: 55px;
            }

            .order-divider {
                border-left: none;
                border-top: 1px solid #B8B9BA;
                padding-top: 10px;
                margin-top: 10px;
                text-align: left !important;
            }

            .order-card .col-md-4 {
                text-align: left !important;
            }

            .order-card .mb-5 {
                margin-bottom: 20px !important;
            }

            .order-card .d-flex.justify-content-between {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .order-card .d-flex.gap-2 {
                width: 100%;
            }

            .order-card .d-flex.gap-2 button {
                flex: 1;
            }

            .order-meta {
                font-size: 12px;
            }

        }
    </style>


    <div class="order-title mb-3">
        Semua Pesanan
    </div>


    <div class="order-search p-2 rounded mb-3 d-flex align-items-center py-0">
        <span class="iconify me-2" data-icon="majesticons:search-line"></span>
        <input type="text" class="form-control border-0 bg-transparent" placeholder="Cari semua pesanan anda disini">
    </div>


    <div class="case">
        <div class="card order-card mb-3">

            <div class="card-body">

                <div class="d-flex justify-content-end mb-2">

                    <span class="badge status-done text-white">
                        Selesai
                    </span>

                </div>

                <div class="row align-items-center mb-5">

                    <div class="col-md-8">

                        <div class="d-flex align-items-center gap-3 order-product">

                            <img src="{{ asset('images/home/category/beras-medium.png') }}">

                            <div>
                                <strong>Beras Putih Premium</strong>
                                <div class="order-meta">x 1</div>
                            </div>

                        </div>

                    </div>

                    <div class="col-md-4 text-end order-divider">

                        <div class="order-meta">Total Pembayaran</div>
                        <strong>Rp.153.000</strong>

                    </div>

                </div>

                <div class="d-flex justify-content-between align-items-center">

                    <div>
                        <strong>Pembelian</strong>
                        <span class="order-meta ms-2">27 Januari 2026</span>
                    </div>

                    <div class="d-flex gap-2">

                        <button class="btn btn-outline-success btn-sm">
                            Detail Transaksi
                        </button>

                        <button class="btn btn-success btn-sm">
                            Nilai
                        </button>

                    </div>

                </div>

            </div>
        </div>
        <div class="card order-card mb-3">

            <div class="card-body">

                <div class="d-flex justify-content-end mb-2">

                    <span class="badge status-done text-white">
                        Selesai
                    </span>

                </div>

                <div class="row align-items-center mb-5">

                    <div class="col-md-8">

                        <div class="d-flex align-items-center gap-3 order-product">

                            <img src="{{ asset('images/home/category/beras-medium.png') }}">

                            <div>
                                <strong>Beras Putih Premium</strong>
                                <div class="order-meta">x 1</div>
                            </div>

                        </div>

                    </div>

                    <div class="col-md-4 text-end order-divider">

                        <div class="order-meta">Total Pembayaran</div>
                        <strong>Rp.153.000</strong>

                    </div>

                </div>

                <div class="d-flex justify-content-between align-items-center">

                    <div>
                        <strong>Pembelian</strong>
                        <span class="order-meta ms-2">27 Januari 2026</span>
                    </div>

                    <div class="d-flex gap-2">

                        <button class="btn btn-outline-success btn-sm">
                            Detail Transaksi
                        </button>

                        <button class="btn btn-success btn-sm">
                            Nilai
                        </button>

                    </div>

                </div>

            </div>
        </div>
        <div class="card order-card mb-3">

            <div class="card-body">

                <div class="d-flex justify-content-end mb-2">

                    <span class="badge status-done text-white">
                        Selesai
                    </span>

                </div>

                <div class="row align-items-center mb-5">

                    <div class="col-md-8">

                        <div class="d-flex align-items-center gap-3 order-product">

                            <img src="{{ asset('images/home/category/beras-medium.png') }}">

                            <div>
                                <strong>Beras Putih Premium</strong>
                                <div class="order-meta">x 1</div>
                            </div>

                        </div>

                    </div>

                    <div class="col-md-4 text-end order-divider">

                        <div class="order-meta">Total Pembayaran</div>
                        <strong>Rp.153.000</strong>

                    </div>

                </div>

                <div class="d-flex justify-content-between align-items-center">

                    <div>
                        <strong>Pembelian</strong>
                        <span class="order-meta ms-2">27 Januari 2026</span>
                    </div>

                    <div class="d-flex gap-2">

                        <button class="btn btn-outline-success btn-sm">
                            Detail Transaksi
                        </button>

                        <button class="btn btn-success btn-sm">
                            Nilai
                        </button>

                    </div>

                </div>

            </div>
        </div>
        <div class="card order-card mb-3">

            <div class="card-body">

                <div class="d-flex justify-content-end mb-2">

                    <span class="badge status-done text-white">
                        Selesai
                    </span>

                </div>

                <div class="row align-items-center mb-5">

                    <div class="col-md-8">

                        <div class="d-flex align-items-center gap-3 order-product">

                            <img src="{{ asset('images/home/category/beras-medium.png') }}">

                            <div>
                                <strong>Beras Putih Premium</strong>
                                <div class="order-meta">x 1</div>
                            </div>

                        </div>

                    </div>

                    <div class="col-md-4 text-end order-divider">

                        <div class="order-meta">Total Pembayaran</div>
                        <strong>Rp.153.000</strong>

                    </div>

                </div>

                <div class="d-flex justify-content-between align-items-center">

                    <div>
                        <strong>Pembelian</strong>
                        <span class="order-meta ms-2">27 Januari 2026</span>
                    </div>

                    <div class="d-flex gap-2">

                        <button class="btn btn-outline-success btn-sm">
                            Detail Transaksi
                        </button>

                        <button class="btn btn-success btn-sm">
                            Nilai
                        </button>

                    </div>

                </div>

            </div>
        </div>
    </div>

    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>

@endsection
