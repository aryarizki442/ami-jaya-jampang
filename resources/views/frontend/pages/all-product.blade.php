@extends('app')

@section('title', 'Semua Produk')

@section('content')

    <style>
        .header-custom {
            background: linear-gradient(90deg, #0D3523, #1F7D53);
            border-radius: 0 0 0px 5px;
            position: sticky;
            top: 0;
            z-index: 1;
        }

        /* .custom-dropdown {
                                                            border: 1px solid #1F7D53;
                                                            border-radius: 6px;
                                                            position: relative;
                                                        }

                                                        .dropdown-list {
                                                            border-top: 1px solid #1F7D53;
                                                            display: none;
                                                        } */

        .produk-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }

        .produk-item {
            flex: 0 0 calc(20% - 12px);
            /* 5 kolom */
        }

        .kategori-tabs {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 25px;
            margin: 0;
        }

        .kategori-tab {
            padding: 10px 5px;

            background: transparent;
            border: none;

            color: #9CA1AA;
            font-size: 15px;
            font-weight: 600;

            cursor: pointer;
            position: relative;
            transition: all .2s ease;
        }

        .kategori-tab:hover {
            color: #1F7D53;
        }

        /* garis bawah saat aktif */
        .kategori-tab.active {
            color: #1F7D53;
        }

        .kategori-tab::after {
            content: "";
            position: absolute;
            left: 0;
            bottom: 3px;

            width: 100%;
            height: 2px;

            background: #1F7D53;

            transform: scaleX(0);
            transform-origin: center;
            transition: transform .25s ease;
        }

        .kategori-tab.active::after {
            transform: scaleX(1);
        }

        .empty-product {
            width: 100%;
            min-height: 300px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: #777;
        }

        .empty-product iconify-icon {
            font-size: 60px;
            color: #1F7D53;
        }

        .empty-product h5 {
            color: #1F7D53;
            font-weight: 600;
        }

        /* tablet */
        @media (max-width: 992px) {
            .produk-item {
                flex: 0 0 calc(33.333% - 12px);
            }
        }

        /* mobile */
        @media (max-width: 576px) {
            .kategori-tabs {
                display: flex;
                justify-content: flex-start;
                overflow-x: auto;
                flex-wrap: nowrap;
                gap: 20px;
                padding: 10px 15px;
            }

            .kategori-tab {
                min-width: max-content;
                padding: 8px 0;

                border: none;
                border-radius: 0;

                font-size: 13px;
                font-weight: 500;
            }

            .kategori-tab.active::after {
                bottom: 0;
            }

            .produk-item {
                flex: 0 0 calc(50% - 12px);
            }

            #pageTitle {
                font-size: 14px;
            }
        }
    </style>

    <!-- HEADER / NAVBAR CUSTOM -->
    <div class="container-fluid py-2 bg-white sticky-top header-custom">

        <div class="header-bg"></div>

        <div class="header-content d-flex align-items-center justify-content-between">

            <img src="{{ asset('images/logo/daun-kiri.png') }}" style="height:60px; opacity: 0.3;">

            <h5 class="m-0 text-white fw-semibold" id="pageTitle">
                Semua Produk Beras
            </h5>

            <img src="{{ asset('images/logo/daun-kanan.png') }}" style="height:60px; opacity: 0.3">

        </div>

    </div>
    <div class="kategori-tabs mt-0 gap-3">
        <button class="kategori-tab active" data-value="">Semua</button>

        @foreach ($categories as $category)
            <button class="kategori-tab" data-value="{{ $category->id }}">
                {{ $category->name }}
            </button>
        @endforeach
    </div>

    <div class="produk-grid mt-4 mb-5" id="produkGrid" style="min-height:300px;">

        @forelse ($products as $product)
            <div class="produk-item" data-category="{{ $product->category_id }}">

                <a href="{{ route('detail-product', $product->slug) }}"
                    class="text-decoration-none text-dark w-100 h-100 d-flex">

                    <div class="produk-card rounded shadow-sm">

                        <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('images/home/category/beras-putih.png') }}"
                            class="produk-img">

                        <div class="produk-body p-2">

                            <div class="rating mb-2">
                                ★★★★★
                            </div>

                            <p class="produk-title mb-2">
                                {{ $product->weight ?? '1 Liter' }} {{ $product->name }}
                            </p>

                            <div class="produk-footer">

                                <span class="harga fw-bold">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </span>

                                <small class="text-muted">
                                    Stok {{ $product->stock ?? 0 }}
                                </small>

                            </div>

                        </div>

                    </div>

                </a>

            </div>


        @empty

            <div class="empty-product">

                <iconify-icon icon="majesticons:search-line"></iconify-icon>

                <h5 class="mt-3">
                    Produk tidak tersedia
                </h5>

                <p>
                    Produk dengan kata kunci
                    <b>"{{ request('search') }}"</b>
                    tidak ditemukan.
                </p>

            </div>
        @endforelse

    </div>


    <script>
        const pageTitle = document.getElementById('pageTitle');

        document.querySelectorAll('.kategori-tab').forEach(tab => {
            tab.addEventListener('click', function() {

                document.querySelectorAll('.kategori-tab')
                    .forEach(btn => btn.classList.remove('active'));

                this.classList.add('active');

                const categoryId = this.dataset.value;
                const categoryName = this.textContent.trim();

                if (categoryId === "") {
                    pageTitle.textContent = "Semua Produk Beras";
                } else {
                    if (categoryName.toLowerCase().includes("premium")) {
                        pageTitle.textContent = "Beras Putih Premium";
                    } else if (categoryName.toLowerCase().includes("medium")) {
                        pageTitle.textContent = "Beras Putih Medium";
                    } else if (categoryName.toLowerCase().includes("ketan")) {
                        pageTitle.textContent = "Beras Ketan";
                    } else {
                        pageTitle.textContent = "Beras " + categoryName;
                    }
                }

                filterProduk(categoryId);
            });
        });

        function filterProduk(categoryId) {
            document.querySelectorAll('.produk-item').forEach(item => {
                const match =
                    categoryId === "" ||
                    item.dataset.category == categoryId;

                item.style.display = match ? "block" : "none";
            });
        }
        const selectedCategory = new URLSearchParams(window.location.search)
            .get('category');

        window.addEventListener('DOMContentLoaded', () => {

            if (!selectedCategory) return;

            const tab = document.querySelector(
                `.kategori-tab[data-value="${selectedCategory}"]`
            );

            if (tab) {
                tab.click();
            }
        });


        function getToken() {
            return localStorage.getItem('token');
        }

        async function loadMe() {
            const token = getToken();

            if (!token) return;

            try {
                const res = await fetch('/api/me', {
                    headers: {
                        'Authorization': 'Bearer ' + token,
                        'Accept': 'application/json'
                    }
                });

                const data = await res.json();
                console.log("User:", data);

            } catch (err) {
                console.error(err);
            }
        }

        loadMe();
    </script>
    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
@endsection
