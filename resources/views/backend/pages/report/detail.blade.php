@extends('backend.app')

@section('title', 'Detail Laporan')

@section('content')

    <style>
        /* GAMBAR FIX SIZE */
        .detail-image {
            max-width: 250px;
            height: auto;
            border-radius: 12px;
        }

        .detail-name {
            font-size: 16px;
            font-weight: 400;
            min-height: 24px;
        }

        .detail-desc {
            line-height: 1.6;
            max-height: 160px;
            overflow-y: auto;
            padding-right: 5px;
            font-size: 16px;
            font-weight: 400;
        }

        .detail-divider {
            border-left: 1px solid #e5e7eb;
            padding-left: 20px;
            height: 100%;
        }

        /* Info tambahan untuk laporan */
        .info-row {
            display: flex;
            margin-bottom: 12px;
        }

        .info-label {
            width: 180px;
            font-weight: 500;
            color: #666;
        }

        .info-value {
            color: #1a1a1a;
        }

        .revenue-text {
            color: #269B66;
            font-weight: 600;
            font-size: 18px;
        }
    </style>

    <div class="row g-4 align-items-stretch">
        <!-- HEADER -->
        <div class="d-flex align-items-center">
            <a href="{{ route('admin.report.index') }}" class="d-flex align-items-center text-decoration-none text-dark">
                <i class="ri-arrow-left-line fs-5 me-2"></i>
                <span class="fw-medium">Kembali</span>
            </a>
        </div>

        <h5 class="fw-semibold">Detail Laporan</h5>

        <div class="card p-4 border-0 shadow-sm mt-3">
            <div class="row align-items-start g-0">

                <!-- GAMBAR -->
                <div class="col-md-auto d-flex align-items-center ps-2 pe-2">
                    <img id="detailImage" src="/images/home/category/beras-putih.png" class="detail-image">
                </div>

                <!-- INFO PRODUK -->
                <div class="col d-flex flex-column ps-2 pe-3">

                    <!-- NAMA PRODUK -->
                    <div class="mb-4">
                        <div class="fw-semibold small">Nama Produk</div>
                        <div id="detailName" class="detail-name">-</div>
                    </div>

                    <!-- DESKRIPSI PRODUK -->
                    <div>
                        <div class="fw-semibold small">Deskripsi Produk</div>
                        <div id="detailDescription" class="detail-desc">-</div>
                    </div>

                </div>
                <!-- SIDE INFO (HARGA, STOK, BERAT, KATEGORI, PENDAPATAN) -->
                <!-- SIDE INFO (HARGA, STOK, BERAT, KATEGORI, TERJUAL, PENDAPATAN) -->
                <!-- SIDE INFO (HARGA, STOK, BERAT, KATEGORI, TERJUAL, PENDAPATAN) -->
                <div class="col-md-3 d-flex flex-column justify-content-start ps-3 detail-divider">
                    <div class="pt-1">
                        <div class="fw-semibold small">Harga Produk (per Karung) :</div>
                        <div id="detailHarga" class="mt-1 mb-2">-</div>

                        <div class="fw-semibold small">Stok Produk (per Karung) :</div>
                        <div id="detailStok" class="mt-1 mb-2">-</div>

                        <div class="fw-semibold small">Berat Produk (per Karung) :</div>
                        <div id="detailBerat" class="mt-1 mb-2">-</div>

                        <div class="fw-semibold small">Kategori Produk :</div>
                        <span id="detailBadge" class="badge bg-success-subtle text-success mt-1 mb-2">-</span>

                        <!-- TERJUAL -->
                        <hr class="my-2">

                        <!-- TERJUAL dengan label dan tanggal sejajar -->
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="fw-semibold small">Terjual :</div>
                            <span id="detailTanggal" class="text-muted small">-</span>
                        </div>

                        <!-- ANGKA TERJUAL -->
                        <div id="detailTerjual" class="fw-semibold mt-1 mb-3">-</div>
                        <!-- PENDAPATAN -->
                        <div class="fw-semibold small mt-2">Pendapatan :</div>
                        <div id="detailPendapatan" class="fw-semibold mt-1" style=" font-size: 16px;">-</div>
                    </div>
                </div>

            </div>

        </div>

        <script>
            // Ambil ID dari URL
            function getReportIdFromUrl() {
                return window.location.pathname.split('/').filter(Boolean).pop();
            }

            // Format Rupiah
            function formatRupiah(angka) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                }).format(angka);
            }

            // Data Dummy Laporan
            const dummyData = [{
                    id: 1,
                    nama_produk: "Beras Putih Rojo Lele",
                    deskripsi: "Beras Rojo Lele merupakan pilihan beras berkualitas unggulan dengan butiran yang putih bersih, utuh, dan minim patahan. Saat dimasak, beras ini menghasilkan nasi yang pulen, harum, dan lezat, sehingga cocok untuk konsumsi sehari-hari maupun hidangan spesial keluarga.",
                    harga: 100000,
                    stok: 200,
                    berat: "50 Kg",
                    kategori: "Premium",
                    terjual: 120,
                    tanggal: "Maret 2026",
                    pendapatan: 12000000,
                    image: "/images/home/category/beras-putih.png"
                },
                {
                    id: 2,
                    nama_produk: "Beras Putih Ramos",
                    deskripsi: "Beras Ramos memiliki kualitas terbaik dengan butiran panjang dan putih bersih. Cocok untuk berbagai masakan sehari-hari karena teksturnya yang pulen dan tidak mudah lembek.",
                    harga: 95000,
                    stok: 180,
                    berat: "50 Kg",
                    kategori: "Medium",
                    terjual: 115,
                    tanggal: "Maret 2026",
                    pendapatan: 11500000,
                    image: "/images/home/category/beras-putih.png"
                },
                {
                    id: 3,
                    nama_produk: "Beras Putih Pandan Wangi",
                    deskripsi: "Beras Pandan Wangi terkenal dengan aroma harum alami seperti pandan. Teksturnya pulen dan cocok untuk hidangan nasi gurih, nasi uduk, atau sekadar nasi putih hangat.",
                    harga: 105000,
                    stok: 150,
                    berat: "50 Kg",
                    kategori: "Premium",
                    terjual: 110,
                    tanggal: "Maret 2026",
                    pendapatan: 11000000,
                    image: "/images/home/category/beras-putih.png"
                },
                {
                    id: 4,
                    nama_produk: "Beras Merah Rojo Lele",
                    deskripsi: "Beras merah organik dengan kandungan serat tinggi. Baik untuk kesehatan pencernaan dan cocok untuk diet sehat. Butiran beras merahnya bersih dan berkualitas.",
                    harga: 120000,
                    stok: 100,
                    berat: "50 Kg",
                    kategori: "Ketan",
                    terjual: 100,
                    tanggal: "Maret 2026",
                    pendapatan: 10000000,
                    image: "/images/home/category/beras-merah.png"
                },
                {
                    id: 5,
                    nama_produk: "Beras Putih BMW",
                    deskripsi: "Beras BMW merupakan pilihan beras berkualitas unggulan dengan butiran yang putih bersih, utuh, dan minim patahan. Saat dimasak, beras ini menghasilkan nasi yang pulen, harum, dan lezat, sehingga cocok untuk konsumsi sehari-hari maupun hidangan spesial keluarga.",
                    harga: 100000,
                    stok: 125,
                    berat: "50 Kg",
                    kategori: "Premium",
                    terjual: 75,
                    tanggal: "Maret 2026",
                    pendapatan: 7500000,
                    image: "/images/home/category/beras-putih.png"
                }
            ];

            document.addEventListener("DOMContentLoaded", function() {
                const id = parseInt(getReportIdFromUrl());

                // Cari data berdasarkan ID
                const data = dummyData.find(item => item.id === id);

                const elImage = document.getElementById('detailImage');
                const elName = document.getElementById('detailName');
                const elDesc = document.getElementById('detailDescription');
                const elBadge = document.getElementById('detailBadge');
                const elHarga = document.getElementById('detailHarga');
                const elStok = document.getElementById('detailStok');
                const elBerat = document.getElementById('detailBerat');
                const elTerjual = document.getElementById('detailTerjual');
                const elTanggal = document.getElementById('detailTanggal');
                const elPendapatan = document.getElementById('detailPendapatan');

                if (!data) {
                    elName.textContent = 'Data tidak ditemukan';
                    return;
                }

                // IMAGE
                elImage.src = data.image || '/images/home/category/beras-putih.png';

                // TEXT DETAIL
                elName.textContent = data.nama_produk;
                elDesc.textContent = data.deskripsi;

                // BADGE KATEGORI
                const badgeMap = {
                    premium: 'premium-category fw-normal',
                    medium: 'medium-category fw-normal',
                    ketan: 'ketan-category fw-normal'
                };
                const key = (data.kategori || '').toLowerCase();
                const badgeClass = badgeMap[key] || 'bg-secondary-subtle text-secondary';
                elBadge.className = `badge ${badgeClass}`;
                elBadge.textContent = data.kategori;

                // INFO PRODUK
                elHarga.textContent = formatRupiah(data.harga);
                elStok.textContent = data.stok;
                elBerat.textContent = data.berat;

                // TERJUAL & PENDAPATAN
                elTerjual.textContent = data.terjual;
                elTanggal.textContent = data.tanggal;
                elPendapatan.textContent = formatRupiah(data.pendapatan);
            });
        </script>

    @endsection
