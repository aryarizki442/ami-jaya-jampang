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
    </style>
    </style>

    <form id="formProduct" enctype="multipart/form-data">
        <div class="row g-4 align-items-stretch">

            <!-- HEADER -->
            <div class="d-flex align-items-center">
                <a href="{{ route('admin.product') }}" class="d-flex align-items-center text-decoration-none text-dark">
                    <i class="ri-arrow-left-line fs-5 me-2"></i>
                    <span class="fw-medium">Kembali</span>
                </a>
            </div>

            <h5 class="fw-semibold">Detail Produk</h5>

            <div class="card p-4 border-0 shadow-sm mt-3">
                <div class="row align-items-start g-0">

                    <!-- IMAGE -->
                    <div class="col-md-auto d-flex align-items-center ps-2 pe-3">
                        <img id="detailImage" src="/images/home/category/beras-putih.png" class="rounded"
                            style="width:140px; height:140px; object-fit:cover;">
                    </div>

                    <!-- INFO -->
                    <div class="col d-flex flex-column ps-2 pe-3">

                        <div class="mb-3">
                            <div class="fw-semibold small text-muted">Nama Produk</div>
                            <div id="detailName" class="fw-medium fs-5">-</div>
                        </div>

                        <div>
                            <div class="fw-semibold small text-muted">Deskripsi</div>
                            <div id="detailDescription" class="text-muted">-</div>
                        </div>

                    </div>

                    <!-- CATEGORY + PRICE -->
                    <div class="col-md-3 ps-3 border-start">

                        <div class="mb-3">
                            <div class="fw-semibold small text-muted">Kategori</div>
                            <span id="detailCategory" class="badge bg-success-subtle text-success">-</span>
                        </div>

                        <div class="mb-3">
                            <div class="fw-semibold small text-muted">Harga</div>
                            <div id="detailPrice" class="fw-semibold">-</div>
                        </div>

                        <div>
                            <div class="fw-semibold small text-muted">Stok</div>
                            <div id="detailStock">-</div>
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

            const id = getProductIdFromUrl();

            const elImage = document.getElementById('detailImage');
            const elName = document.getElementById('detailName');
            const elDesc = document.getElementById('detailDescription');
            const elBadge = document.getElementById('detailBadge');

            const inputName = document.getElementById('name');
            const inputDesc = document.getElementById('description');
            const inputActive = document.getElementById('isActive');

            try {

                const res = await fetch(`/api/admin/products/${id}`, {
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                const json = await res.json();
                const data = json.data;

                if (!data) return;

                /* =========================
                   IMAGE HANDLER
                ========================== */
                const image = data.image ?
                    (data.image.startsWith('http') ?
                        data.image :
                        `/storage/${data.image.replace(/^\/+/, '')}`) :
                    '/images/home/category/beras-putih.png';

                elImage.src = image;

                /* =========================
                   TEXT DETAIL
                ========================== */
                elName.textContent = data.name ?? '-';
                elDesc.textContent = data.description ?? '-';

                /* =========================
                   BADGE COLOR
                ========================== */
                const badgeMap = {
                    premium: 'bg-success-subtle text-success',
                    medium: 'bg-warning-subtle text-warning',
                    ketan: 'bg-info-subtle text-info'
                };

                const key = (data.name || '').toLowerCase();
                const badgeClass = badgeMap[key] || 'bg-secondary-subtle text-secondary';

                elBadge.className = `badge ${badgeClass}`;
                elBadge.textContent = data.name ?? '-';

                /* =========================
                   FORM FILL (EDIT MODE)
                ========================== */
                if (inputName) inputName.value = data.name ?? '';
                if (inputDesc) inputDesc.value = data.description ?? '';
                if (inputActive) inputActive.checked = !!data.is_active;

            } catch (err) {
                console.error('Fetch detail error:', err);
            }

        });
    </script>
@endsection
