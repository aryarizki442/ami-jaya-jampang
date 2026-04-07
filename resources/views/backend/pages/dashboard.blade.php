@extends('backend.app')

@section('title', 'Dashboard')

@section('page-title', 'Dashboard')

@section('content')
    <style>
        /* GLOBAL FONT SIZE (lebih kecil & rapi) */
        body {
            font-size: 13px;
        }

        h4 {
            font-size: 18px;
            margin: 0;
        }

        h6 {
            font-size: 13px;
            margin-bottom: 5px;
        }

        .card-header span {
            font-size: 13px;
            font-weight: 600;
        }

        .card-header small {
            font-size: 11px;
        }

        .table th,
        .table td {
            font-size: 12px;
            padding: 8px;
        }

        .badge {
            font-size: 11px;
        }

        /* EQUAL HEIGHT */
        .row-equal {
            display: flex;
            flex-wrap: wrap;
        }

        .row-equal>[class*='col-'] {
            display: flex;
        }

        /* CARD GRADIENT */
        .card-custom {
            width: 100%;
            display: flex;
            flex-direction: column;
            background: linear-gradient(90deg, #0D3523, #1F7D53);
            border-radius: 10px;
            color: #fff;
            overflow: hidden;
        }

        .card-custom .card-body {
            flex: 1;
        }

        /* TABLE FULL HEIGHT */
        .table {
            height: 100%;
        }

        /* .table thead th {
                                                        color: #D1D3D8 !important;
                                                        font-weight: 500;
                                                    } */
    </style>

    <!-- CARD ATAS -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6>Total Pesanan</h6>
                    <h4>350 Karung</h4>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6>Pendapatan Hari ini</h6>
                    <h4>Rp. 1.500.000</h4>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6>Total Produk</h6>
                    <h4>25 Item</h4>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6>Stok Hampir Habis</h6>
                    <h4>3 Item</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- ROW 1 -->
    <div class="row row-equal">
        <div class="col-md-8">
            <div class="card card-custom shadow-sm border-0">
                <div class="card-header d-flex justify-content-between">
                    <span>Grafik Penjualan Bulanan</span>
                    <small>Lihat Semua</small>
                </div>
                <div class="card-body p-0">
                    <div style="height:100%; min-height:250px; background:#fff;"></div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-custom shadow-sm border-0">
                <div class="card-header d-flex justify-content-between">
                    <span>Produk Terlaris</span>
                    <small>Lihat Semua</small>
                </div>
                <div class="card-body p-0 text-center">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Nama Produk</th>
                                <th>Produk Terjual</th>
                                <th>Kategori</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-start">Beras Pandan Wangi
                                    <br>
                                </td>
                                <td>23</td>
                                <td><span class="badge bg-success-subtle text-success fw-normal">Premium</span></td>
                            </tr>
                            <tr>
                                <td class="text-start">Beras Ketan</td>
                                <td>15</td>
                                <td><span class="badge bg-warning-subtle text-warning fw-normal">Medium</span></td>
                            </tr>
                            <tr>
                                <td class="text-start">Beras Rojolele</td>
                                <td>20</td>
                                <td><span class="badge bg-info-subtle text-info fw-normal">Ketan</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- ROW 2 -->
    <div class="row mt-4 row-equal">
        <div class="col-md-8">
            <div class="card card-custom shadow-sm border-0">
                <div class="card-header d-flex justify-content-between">
                    <span>Pesanan Terbaru</span>
                    <small>Lihat Semua</small>
                </div>
                <div class="card-body p-0 text-center">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tanggal</th>
                                <th>Pelanggan</th>
                                <th>Total</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>INV001</td>
                                <td>10/02/2026</td>
                                <td>Malik</td>
                                <td>Rp.850.000</td>
                                <td><span class="badge bg-success-subtle text-success fw-normal">Dibayar</span></td>
                            </tr>
                            <tr>
                                <td>INV002</td>
                                <td>09/02/2026</td>
                                <td>Pudji</td>
                                <td>Rp.550.000</td>
                                <td><span class="badge bg-danger-subtle text-danger fw-normal">Dibatalkan</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-custom shadow-sm border-0">
                <div class="card-header d-flex justify-content-between">
                    <span>Stok Hampir Habis</span>
                    <small>Lihat Semua</small>
                </div>
                <div class="card-body p-0">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th class="text-center">Sisa Stok</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Beras Pandan Wangi</td>
                                <td class="text-center">7</td>
                            </tr>
                            <tr>
                                <td>Beras Rojolele</td>
                                <td class="text-center">5</td>
                            </tr>
                            <tr>
                                <td>Beras Ketan</td>
                                <td class="text-center">2</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection
