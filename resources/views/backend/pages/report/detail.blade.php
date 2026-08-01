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


            const API = {
                baseUrl: '/api/admin/reports',

                async getDetail(productId) {
                    const response = await fetch(`${this.baseUrl}/${productId}`, {
                        headers: {
                            Accept: 'application/json'
                        }
                    });

                    if (!response.ok) {
                        throw new Error(`HTTP ${response.status}`);
                    }

                    return await response.json();
                }
            };
            document.addEventListener("DOMContentLoaded", async function() {

                const id = getReportIdFromUrl();

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

                try {

                    const response = await API.getDetail(id);

                    if (!response.success) {
                        throw new Error('Data tidak ditemukan');
                    }

                    const product = response.data.product;
                    const report = response.data.report;

                    elImage.src = product.image || '/images/no-image.png';

                    elName.textContent = product.name ?? '-';

                    elDesc.textContent = product.description ?? '-';

                    elHarga.textContent =
                        product.price_format ?? formatRupiah(product.price);

                    elStok.textContent = product.stock ?? "-";

                    elBerat.textContent =
                        (product.weight_kg ?? "-") + " Kg";

                    elBadge.textContent =
                        product.category ?? '-';

                    elTerjual.textContent =
                        report.total_sold ?? 0;

                    elTanggal.textContent =
                        report.period ?? '-';

                    elPendapatan.textContent =
                        report.total_revenue_format ??
                        formatRupiah(report.total_revenue ?? 0);

                    // Badge warna
                    const badgeMap = {
                        premium: 'premium-category fw-normal',
                        medium: 'medium-category fw-normal',
                        ketan: 'ketan-category fw-normal'
                    };

                    const key = (product.category || '').toLowerCase();

                    elBadge.className =
                        `badge ${badgeMap[key] || 'bg-secondary-subtle text-secondary'}`;

                } catch (err) {

                    console.error(err);

                    elName.textContent = 'Gagal memuat data';
                }

            });
        </script>

    @endsection
