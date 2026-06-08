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

        .custom-dropdown {
            border: 1px solid #1F7D53;
            border-radius: 6px;
            position: relative;
        }

        .dropdown-list {
            border-top: 1px solid #1F7D53;
            display: none;
        }

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
            gap: 10px;
            margin: 0;
        }

        .kategori-tab {
            min-width: 110px;
            padding: 10px 20px;

            border: 1px solid #1F7D53;
            border-top: 0;

            border-radius: 0 0 8px 8px;

            background: #fff;
            color: #1F7D53;

            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all .2s ease;
        }

        .kategori-tab:hover {
            background: #f3faf6;
            transform: translateY(-1px);
        }

        .kategori-tab.active {
            background: #1F7D53;
            color: #fff;
            border-color: #1F7D53;
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
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 8px;
                width: 100%;
            }

            .kategori-tab {
                width: 100%;
                min-width: unset;
                padding: 10px 12px;
                border: 1px solid #1F7D53;
                border-radius: 8px;
                font-size: 12px;
                font-weight: 500;
                text-align: center;
                margin-top: 10px;
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

    <div class="produk-grid mt-4 mb-5">

        @forelse ($products as $product)
            <div class="produk-item" data-category="{{ $product->category_id }}">

                <a href="{{ route('detail-product', $product->slug) }}"
                    class="text-decoration-none text-dark w-100 h-100 d-flex">

                    <div class="produk-card rounded shadow-sm">

                        <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('images/home/category/beras-putih.png') }}"
                            class="produk-img">

                        <div class="produk-body p-2">

                            <div class="rating mb-2">★★★★★</div>

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
            <div class="col-12 text-center">
                <p class="text-muted">Produk belum tersedia</p>
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
