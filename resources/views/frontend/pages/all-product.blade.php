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

        .kategori-wrapper {
            width: 180px;
            position: relative;
            margin-left: auto;
        }

        .kategori-selected {
            border-width: 0px 1px 1px 1px;
            border-style: solid;
            border-color: #1F7D53;

            padding: 8px 10px;

            display: flex;
            justify-content: space-between;
            align-items: center;

            cursor: pointer;

            color: #1F7D53;
        }

        .kategori-dropdown {
            display: none;
            position: absolute;
            width: 100%;
            background: white;
            border: 1px solid #1F7D53;
            border-top: none;
            z-index: 1000;
        }

        .kategori-item {
            padding: 8px 10px;
            text-align: center;
            cursor: pointer;
        }

        .kategori-item:hover {
            background: #e9f7ef;
        }

        .kategori-item.active {
            font-weight: bold;
            color: #1F7D53;
        }

        /* tablet */
        @media (max-width: 992px) {
            .produk-item {
                flex: 0 0 calc(33.333% - 12px);
            }
        }

        /* mobile */
        @media (max-width: 576px) {
            .produk-item {
                flex: 0 0 calc(50% - 12px);
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
    <div class="kategori-wrapper">
        <div class="kategori-selected fw-semibold" id="categoryBtn">
            <span id="selectedText">Kategori</span>

            <span class="icon" id="arrowIcon">
                <iconify-icon icon="iconamoon:arrow-down-2-light"></iconify-icon>
            </span>
        </div>

        <div class="kategori-dropdown" id="categoryDropdown">
            <div class="kategori-item active fw-semibold" data-value="">
                Semua
            </div>

            @foreach ($categories as $category)
                <div class="kategori-item fw-semibold" data-value="{{ $category->id }}" data-name="{{ $category->name }}">
                    {{ $category->name }}
                </div>
            @endforeach
        </div>
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
        const btn = document.getElementById('categoryBtn');
        const dropdown = document.getElementById('categoryDropdown');
        const selectedText = document.getElementById('selectedText');
        const arrowIcon = document.getElementById('arrowIcon');
        const pageTitle = document.getElementById('pageTitle');

        let isOpen = false;

        /* =========================
           TOKEN HELPER (BARU)
        ========================= */
        function getToken() {
            return localStorage.getItem('token');
        }

        /* =========================
           CONTOH CALL /api/me (BARU)
        ========================= */
        async function loadMe() {
            const token = getToken();

            if (!token) {
                console.log("No token found");
                return;
            }

            try {
                const res = await fetch('/api/me', {
                    method: 'GET',
                    headers: {
                        'Authorization': 'Bearer ' + token,
                        'Accept': 'application/json'
                    }
                });

                if (res.status === 401) {
                    console.log("Unauthorized - token invalid");
                    return;
                }

                const data = await res.json();
                console.log("User:", data);

            } catch (err) {
                console.error(err);
            }
        }

        /* =========================
           DROPDOWN TOGGLE
        ========================= */
        btn.addEventListener('click', () => {
            isOpen = !isOpen;
            dropdown.style.display = isOpen ? 'block' : 'none';

            arrowIcon.innerHTML = isOpen ?
                `<iconify-icon icon="iconamoon:arrow-up-2-light"></iconify-icon>` :
                `<iconify-icon icon="iconamoon:arrow-down-2-light"></iconify-icon>`;
        });

        /* =========================
           CLICK CATEGORY
        ========================= */
        document.querySelectorAll('.kategori-item').forEach(item => {
            item.addEventListener('click', function() {

                const name = this.dataset.name;
                const value = this.dataset.value;

                selectedText.textContent = this.textContent;

                if (value === "") {
                    pageTitle.textContent = "Semua Produk Beras";
                } else {
                    if (name.toLowerCase().includes("premium")) {
                        pageTitle.textContent = "Beras Putih Premium";
                    } else if (name.toLowerCase().includes("medium")) {
                        pageTitle.textContent = "Beras Putih Medium";
                    } else if (name.toLowerCase().includes("ketan")) {
                        pageTitle.textContent = "Beras Ketan";
                    } else {
                        pageTitle.textContent = "Beras " + name;
                    }
                }

                dropdown.style.display = 'none';
                isOpen = false;

                arrowIcon.innerHTML =
                    `<iconify-icon icon="iconamoon:arrow-down-2-light"></iconify-icon>`;

                document.querySelectorAll('.kategori-item')
                    .forEach(i => i.classList.remove('active'));

                this.classList.add('active');

                filterProduk(value);
            });
        });

        /* =========================
           FILTER PRODUK
        ========================= */
        function filterProduk(categoryId) {
            document.querySelectorAll('.produk-item').forEach(item => {
                const match = categoryId === "" || item.dataset.category == categoryId;
                item.style.display = match ? "block" : "none";
            });
        }

        /* =========================
           CLOSE OUTSIDE CLICK
        ========================= */
        document.addEventListener('click', function(e) {
            if (!btn.contains(e.target)) {
                dropdown.style.display = 'none';
                isOpen = false;

                arrowIcon.innerHTML =
                    `<iconify-icon icon="iconamoon:arrow-down-2-light"></iconify-icon>`;
            }
        });

        /* OPTIONAL: auto load user */
        loadMe();
    </script>
    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
@endsection
