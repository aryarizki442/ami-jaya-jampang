@extends('backend.app')

@section('title', 'Produk')

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

        .detail-image {
            width: 250px;
            height: 250px;
            object-fit: contain;
            /* ini kuncinya */
            flex-shrink: 0;
            background: #fff;
            /* biar kalau ada space kosong nggak aneh */
        }
    </style>
    </style>

    <form id="formProduct" enctype="multipart/form-data">
        <div class="row g-4 align-items-stretch">

            <!-- HEADER -->
            <div class="d-flex align-items-center">
                <a href="{{ route('admin.product.index') }}" class="d-flex align-items-center text-decoration-none text-dark">
                    <i class="ri-arrow-left-line fs-5 me-2"></i>
                    <span class="fw-medium">Kembali</span>
                </a>
            </div>

            <h5 class="fw-semibold">Detail Produk</h5>

            <div class="card p-4 border-0 shadow-sm mt-3">
                <div class="row align-items-start g-0">

                    <!-- GAMBAR PRODUK -->
                    <div class="col-md-auto d-flex align-items-center ps-2 pe-3">
                        <img id="detailImage" src="/images/home/category/beras-putih.png" class="detail-image">
                    </div>

                    <!-- INFO PRODUK -->
                    <div class="col d-flex flex-column ps-2 pe-3">

                        <!-- NAMA -->
                        <div class="mb-3">
                            <div class="fw-semibold small text-muted">Nama Produk</div>
                            <div id="detailName" class="detail-name">-</div>
                        </div>

                        <!-- DESKRIPSI -->
                        <div>
                            <div class="fw-semibold small text-muted">Deskripsi Produk</div>
                            <div id="detailDescription" class="detail-desc">-</div>
                        </div>

                    </div>

                    <!-- INFO SAMPING -->
                    <div class="col-md-3 d-flex flex-column justify-content-start ps-3 detail-divider">

                        <div class="pt-1 mb-3">
                            <div class="fw-semibold small text-muted">Harga</div>
                            <div id="detailPrice">-</div>
                        </div>

                        <div class="mb-3">
                            <div class="fw-semibold small text-muted">Stok</div>
                            <div id="detailStock">-</div>
                        </div>

                        <div class="mb-3">
                            <div class="fw-semibold small text-muted">Berat</div>
                            <div id="detailWeight">-</div>
                        </div>

                        <div>
                            <div class="fw-semibold small text-muted">Kategori</div>
                            <span id="detailCategory" class="badge bg-success-subtle text-success">-</span>
                        </div>

                    </div>

                </div>
            </div>
        </div>

    </form>

    <script>
        function getProductIdFromUrl() {
            return window.location.pathname.split('/').filter(Boolean).pop();
        }
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", async function() {

            const id = window.location.pathname.split('/').filter(Boolean).pop();

            const elImage = document.getElementById('detailImage');
            const elName = document.getElementById('detailName');
            const elDesc = document.getElementById('detailDescription');
            const elCategory = document.getElementById('detailCategory');
            const elPrice = document.getElementById('detailPrice');
            const elStock = document.getElementById('detailStock');
            const elWeight = document.getElementById('detailWeight');

            try {
                const res = await fetch(`/api/admin/products/${id}`, {
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                const json = await res.json();
                const data = json.data;

                if (!data) return;

                // =========================
                // IMAGE
                // =========================
                const image = data.image ?
                    (data.image.startsWith('http') ?
                        data.image :
                        `/storage/${data.image.replace(/^\/+/, '')}`) :
                    '/images/home/category/beras-putih.png';

                elImage.src = image;

                // =========================
                // TEXT
                // =========================
                elName.textContent = data.name ?? '-';
                elDesc.textContent = data.description ?? '-';

                // =========================
                // CATEGORY (FIXED)
                // =========================
                const categoryName = data.category?.name ?? '-';

                const badgeMap = {
                    premium: 'premium-category fw-normal',
                    medium: 'medium-category fw-normal',
                    ketan: 'ketan-category fw-normal'
                };

                const key = categoryName.toLowerCase();
                const badgeClass = badgeMap[key] || 'bg-secondary-subtle text-secondary';

                elCategory.className = `badge ${badgeClass}`;
                elCategory.textContent = categoryName;

                // =========================
                // PRICE + STOCK
                // =========================
                elPrice.textContent = data.price_format ?? `Rp ${data.price}`;
                elStock.textContent = `${data.stock ?? 0}`;
                elWeight.textContent = data.weight_kg ? `${data.weight_kg} Kg` : '-';

            } catch (err) {
                console.error('Fetch detail error:', err);
            }

        });
    </script>
@endsection
