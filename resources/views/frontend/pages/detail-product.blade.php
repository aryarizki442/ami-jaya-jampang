@extends('app')

@section('title', 'Deskripsi Produk')

@section('content')
    <style>
        .title-product {
            font-size: 28px;
        }

        .price-product {
            font-size: 26px;
            color: var(--primary-500);
        }

        .qty-group {
            border: 1px solid #adadad;
            border-radius: 5px;
            width: 130px;
            height: 40px;
            overflow: hidden;
            display: flex;
            align-items: center;
        }

        .qty-group .qty-btn {
            background-color: #fff;
            padding: 0 12px;
            font-size: 18px;
            border: none;
            color: #555;
            height: 100%;
        }

        .qty-group .qty-btn {
            transition: all 0.2s ease;
        }

        .qty-group .qty-btn:hover {
            transform: scale(1.2);
        }


        .qty-group .form-control {
            border: none;
            height: 100%;
            text-align: center;
            font-weight: 600;
        }

        .section-header {
            position: relative;
            border-top: 2px solid #adadad;
            border-bottom: 2px solid #adadad;
            /* garis abu full */
        }

        .section-title {
            display: inline-block;
            padding: 10px 50px;
            font-weight: 600;
            font-size: 18px;
            color: #1F7D53;
            border-bottom: 3px solid #1F7D53;
            /* garis hijau pendek */
            margin-bottom: -1px;
            /* nempel ke garis abu */
        }

        .custom-border {
            border-bottom: 2px solid #adadad;
        }

        .breadcrumb-item+.breadcrumb-item::before {
            display: none;
        }
    </style>

    {{-- DESKRIPSI PRODUK --}}
    <div class="row g-4 py-5">
        {{-- GAMBAR PRODUK --}}
        <div class="d-flex align-items-center gap-5 mb-3">

            <h5 class="fw-bold mb-0 title-product">
                Deskripsi Produk
            </h5>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb small mb-0 align-items-center ">

                    <li class="breadcrumb-item">
                        <a href="{{ route('home') }}" class="text-decoration-none text-muted">
                            Home
                        </a>
                    </li>

                    <li class="breadcrumb-item d-flex align-items-center active text-dark fw-semibold" aria-current="page">
                        <span class="iconify mx-2 text-muted" data-icon="solar:alt-arrow-right-linear"></span>

                        {{ $product->name }}
                    </li>

                </ol>
            </nav>

        </div>
        {{-- GAMBAR --}}
        <div class="col-lg-4">

            <div class="text-center">

                <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('images/home/category/beras-putih.png') }}"
                    class="img-fluid" alt="{{ $product->name }}">

            </div>
        </div>

        {{-- DETAIL PRODUK --}}
        <div class="col-lg-5">
            <div class="text-warning small mb-1 fs-3">
                ★★★★☆
            </div>

            <h5 class="fw-semibold mb-2">
                {{ $product->name }}<br>
            </h5>

            <div class="d-flex gap-4 small mb-2">
                <span class="text-muted">
                    Stok:
                    <span class="text-success fw-medium">{{ $product->stock > 0 ? 'Tersedia' : 'Habis' }}</span>
                </span>
                <span class="text-muted">
                    Kategori:
                    <span class="text-dark fw-medium">{{ $product->category->name ?? '-' }}</span>
                </span>
            </div>

            <p class="small text-muted mb-2">
                Terjual:
                <span class="text-dark fw-medium">{{ $product->sold ?? 0 }} Karung</span>
            </p>

            <h4 class="fw-bold price-product pb-2">
                Rp. {{ number_format($product->price, 0, ',', '.') }}
            </h4>

            <div class="section-header mb-3">
                <span class="section-title">Detail Produk</span>
            </div>




            <div class="small mb-2">
                <div class="d-flex mb-1">
                    <div class="text-muted" style="width: 130px;">Berat Per Karung</div>
                    <div class="text-muted" style="width: 10px;">:</div>
                    <div class="text-dark fw-medium">{{ $product->weight_kg ?? '1' }} Kg</div>
                </div>

                {{-- <div>
                    <div class="text-muted" style="width: 140px;">Pengiriman <span style="margin-left: 46px;">:</span>
                    </div>


                    <br>
                    <div class="text-dark fw-medium">*Pembelian <span style="color: #22C55E">Lebih</span> dari 15 karung
                        <br>
                        <div>(<span class="text-muted">Di antar ke alamat Pembeli dan Pick Up</span>)</div>
                    </div>
                    <br>
                    <div class="text-dark fw-medium mb-3">*Pembelian <span style="color: #F59E0B">Kurang</span> dari 15
                        karung
                        <br>
                        <div>(<span class="text-muted"> Pick Up ( Pembeli ambil beras ke Toko ) </span>)</div>
                    </div>
                </div> --}}
            </div>

            {{-- CATEGORY NAME --}}
            <div class="border-bottom pb-2 mb-2" style="border-bottom: 2px solid #ddd !important;">
                <p class="small text-dark fw-semibold mb-0">
                    {{ $product->category->name ?? 'Tidak ada kategori' }}
                </p>

                <p class="small text-dark fw-medium mb-0">
                    {{ $product->category->description ?? 'Tidak ada deskripsi kategori' }}
                </p>
            </div>

            <div class="border-bottom pb-2 mb-2" style="border-bottom: 2px solid #ddd !important;">
                <p class="small text-dark fw-bold mb-0">
                    {{ $product->name ?? 'Tidak ada nama produk' }}
                </p>

                <p class="small text-dark fw-medium mb-0">
                    {{ $product->description ?? 'Tidak ada deskripsi produk' }}
                </p>
            </div>


        </div>

        {{-- ATUR JUMLAH --}}
        <div class="col-lg-3">
            <div class=" rounded p-3 bg-white">
                <h6 class="fw-bold mb-3">Atur Jumlah Dan Catatan</h6>

                <!-- JUMLAH + STOK -->
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <!-- Input Group -->
                    <div class="input-group input-group-sm qty-group">
                        <button class="btn qty-btn fw-bold" data-action="minus">-</button>
                        <input type="text" class="form-control text-center fw-semibold qty-input" value="1"
                            data-price="{{ $product->price }}">
                        <button class="btn qty-btn fw-bold" data-action="plus">+</button>
                    </div>



                    <!-- Stok -->
                    {{-- <span class="small fw-medium">
                        Stok: {{ $product->stock ?? 0 }} Karung
                    </span> --}}
                </div>

                <!-- SUBTOTAL -->
                <div class="d-flex justify-content-between fw-bold mb-3">
                    <span class="fw-medium">Subtotal</span>
                    <span id="subtotal">
                        Rp. {{ number_format($product->price, 0, ',', '.') }}
                    </span>
                </div>

                <button class="btn btn-main w-100 mb-2">
                    + Keranjang
                </button>

                <button class="btn btn-second w-100">
                    Beli Langsung
                </button>
            </div>
        </div>



        {{-- ULASAN --}}
        <div class="mt-5">
            <h6 class="fw-bold text-custom-green mb-3">Ulasan Pembeli</h6>
            <div class="border rounded p-4 text-muted text-center">
                Belum ada ulasan
            </div>
        </div>

        {{-- PILIHAN LAINNYA --}}
        <div class="mt-5">
            <h6 class="fw-bold text-custom-green mb-3">Pilihan Lainnya</h6>

            <div class="row g-3 produk-row">
                @forelse ($products as $product)
                    <div class="produk-col">
                        <a href="{{ route('detail-product', $product->slug) }}" class="text-decoration-none text-dark">
                            <div class="produk-card rounded">

                                <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('images/home/category/beras-putih.png') }}"
                                    class="img-fluid" alt="{{ $product->name }}">
                                <div class="produk-body">

                                    <!-- ⭐ TETAP STATIS SESUAI PERMINTAAN -->
                                    <div class="rating mb-3">★★★★★</div>

                                    <p class="produk-title mb-3">
                                        {{ $product->weight ?? '1 Liter' }} {{ $product->name }}<br>
                                    </p>

                                    <div class="produk-footer">
                                        <span class="harga">
                                            Rp. {{ number_format($product->price, 0, ',', '.') }}
                                        </span>

                                        <span class="terjual">
                                            Tersedia {{ $product->stock ?? 0 }}
                                        </span>
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
        </div>

    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const qtyGroup = document.querySelector('.qty-group');
            const input = document.querySelector('.qty-input');
            const subtotalEl = document.getElementById('subtotal');

            const price = parseInt(input.dataset.price || 0);

            function updateSubtotal() {
                let qty = parseInt(input.value) || 1;
                let total = qty * price;

                subtotalEl.innerText = 'Rp. ' + total.toLocaleString('id-ID');
            }

            qtyGroup.querySelectorAll('.qty-btn').forEach(btn => {
                btn.addEventListener('click', function() {

                    let qty = parseInt(input.value) || 1;

                    if (btn.dataset.action === 'plus') {
                        qty++;
                    } else if (btn.dataset.action === 'minus') {
                        qty = qty > 1 ? qty - 1 : 1;
                    }

                    input.value = qty;
                    updateSubtotal();
                });
            });

            // init pertama kali
            updateSubtotal();

        });
    </script>
@endsection
