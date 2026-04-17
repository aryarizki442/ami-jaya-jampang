<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice</title>


    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!-- CUSTOM CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/color.css') }}">
    <link rel="stylesheet" href="{{ asset('css/login/login.css') }}">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <link rel="stylesheet" href="{{ asset('css/button.css') }}">


    <style>
        body {
            background-color: #fff;
        }

        .navbar-bg {
            background-color: #fff;
        }
    </style>
</head>

<body min-vh-100">
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm px-4 py-2">
        <div class="container-fluid">

            <!-- RIGHT: BUTTON -->
            <div class="ms-auto">
                <button class="btn btn-success">
                    Cetak
                </button>
            </div>

        </div>
    </nav>
    <div class="container py-4 d-flex justify-content-center">
        <div class="bg-white rounded p-2 w-100" style="max-width: 1000px; min-height: 600px;">

            <!-- HEADER CARD -->
            <div class="d-flex justify-content-between align-items-center mb-4">

                <a class="navbar-brand fw-bold d-flex align-items-center mb-0" href="/">
                    <img src="{{ asset('images/logo/logo-hijau.png') }}" width="300">
                </a>
                <div>
                    <h5 class="fw-bold mb-0 text-end">INVOICE</h5>
                    <span class="text-custom-green">INV/2312313/MPL/1241231</span>
                </div>

            </div>

            <!-- CONTENT SEMENTARA -->
            <div class="position-relative">

                <!-- WATERMARK LUNAS -->
                <div
                    style="
        position:absolute;
        top:50%;
        left:50%;
        transform:translate(-50%, -50%) rotate(-25deg);
        font-size:120px;
        color:rgba(40,167,69,0.12);
        font-weight:900;
        pointer-events:none;
        z-index:0;
    ">
                    LUNAS
                </div>

                <!-- CONTENT -->
                <div style="position:relative; z-index:1; ">

                    <div class="row mb-4">
                        <div class="col-md-8">
                            <table class="table table-borderless table-sm mb-0 align-middle"
                                style="table-layout: fixed; width:100%; background: transparent;">

                                <tr>
                                    <td class="text-muted" style="width:220px; background: transparent;">Pembeli</td>
                                    <td style="width:20px; text-align:center; background: transparent;">:</td>
                                    <td style="background: transparent;">Malik Hassan</td>
                                </tr>
                                <tr>
                                    <td class="text-muted" style="background: transparent;">Tanggal Pembelian</td>
                                    <td style="text-align:center; background: transparent;">:</td>
                                    <td style="background: transparent;">23 November 2026</td>
                                </tr>
                                <tr>
                                    <td class="text-muted align-top" style="background: transparent;">Alamat Pengiriman
                                    </td>
                                    <td class="text-center align-top" style="background: transparent;">:</td>
                                    <td class="align-top" style="background: transparent;">
                                        Malik Hassan <span class="text-muted">(0812233445566)</span><br>
                                        <span class="text-muted">
                                            Perumahan Cibinong RT III RW 222 Cibinong,<br>
                                            Kabupaten Bogor, Jawa Barat 16345.
                                        </span>
                                    </td>
                                </tr>

                            </table>
                        </div>
                    </div>

                    <div class="table-responsive mb-4">
                        <table class="table align-middle" style="background: transparent;">
                            <thead
                                style="border-top:2px solid #000; border-bottom:2px solid #000; background: transparent;">
                                <tr>
                                    <th style="background: transparent;">INFO PRODUK</th>
                                    <th style="background: transparent;">JUMLAH</th>
                                    <th style="background: transparent;">HARGA PERKARUNG</th>
                                    <th class="text-end" style="background: transparent;">TOTAL HARGA</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr style="background: transparent;">
                                    <td class="text-custom-green" style="background: transparent;">
                                        1 Kg Beras Putih Premium Rojo Lele
                                    </td>
                                    <td style="background: transparent;">15 Karung</td>
                                    <td style="background: transparent;">Rp. 100.000</td>
                                    <td class="text-end" style="background: transparent;">Rp. 1.500.000</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="row align-items-end mt-3">

                        <!-- KIRI: METODE PEMBAYARAN -->
                        <div class="col-md-6">
                            <small class="text-muted">
                                Metode Pembayaran : <span class="fw-semibold">BCA Virtual Account</span>
                            </small>
                        </div>

                        <!-- KANAN: TOTAL -->
                        <div class="col-md-6">
                            <table class="table table-borderless mb-0">
                                <tr>
                                    <td class="fw-semibold">SUBTOTAL BARANG</td>
                                    <td class="text-end">Rp. 1.500.000</td>
                                </tr>
                                <tr>
                                    <td>Total Ongkos Kirim</td>
                                    <td class="text-end">Rp. 50.000</td>
                                </tr>
                                <tr class="fw-bold border-top">
                                    <td>TOTAL BAYAR</td>
                                    <td class="text-end">Rp. 1.550.000</td>
                                </tr>
                            </table>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</body>
<!-- ICONIFY (WAJIB biar icon muncul) -->
<script src="https://code.iconify.design/3/3.1.1/iconify.min.js"></script>
<script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
<script src="{{ asset('js/main.js') }}"></script>

</html>
