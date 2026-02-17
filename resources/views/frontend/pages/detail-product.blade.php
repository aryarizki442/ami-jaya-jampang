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
    </style>

    {{-- DESKRIPSI PRODUK --}}
    <div class="row g-4">
        {{-- GAMBAR PRODUK --}}
        <h5 class="fw-bold mb-3 title-product">Deskripsi Produk</h5>
        <div class="col-lg-4">

            <div class="text-center">
                <img src="{{ asset('images/home/category/beras-putih.png') }}" class="img-fluid" alt="Beras Putih"
                    width="250">

                <div class="d-flex justify-content-center gap-3 mb-4">
                    <img src="{{ asset('images/home/category/beras-putih.png') }}" class="rounded-circle" width="90">
                    <img src="{{ asset('images/home/category/beras-putih.png') }}" class="rounded-circle" width="90">
                    <img src="{{ asset('images/home/category/beras-putih.png') }}" class="rounded-circle" width="90">
                </div>
            </div>
        </div>

        {{-- DETAIL PRODUK --}}
        <div class="col-lg-5">
            <div class="text-warning small mb-1 fs-3">
                ★★★★☆
            </div>

            <h5 class="fw-semibold mb-2">
                1 Karung Beras Putih Premium<br>Rojo Lele
            </h5>

            <div class="d-flex gap-4 small mb-2">
                <span class="text-muted">
                    Stok:
                    <span class="text-success fw-medium">Tersedia</span>
                </span>
                <span class="text-muted">
                    Kategori:
                    <span class="text-dark fw-medium">Beras Putih Premium</span>
                </span>
            </div>

            <p class="small text-muted mb-2">
                Terjual:
                <span class="text-dark fw-medium">45 Karung</span>
            </p>

            <h4 class="fw-bold price-product pb-2">
                Rp. 100.000
            </h4>

            <div class="section-header mb-3">
                <span class="section-title">Detail Produk</span>
            </div>




            <div class="small mb-2">
                <div class="d-flex mb-1">
                    <div class="text-muted" style="width: 140px;">Berat</div>
                    <div class="text-dark fw-medium">: 50 Kg</div>
                </div>

                <div class="d-flex">
                    <div class="text-muted" style="width: 140px;">Min. Pengiriman</div>
                    <div class="text-dark fw-medium">: 15 Karung</div>
                </div>
            </div>


            <p class="small text-dark fw-medium mb-0 pb-3 custom-border mb-5">
                Lorem ipsum beras merah Lorem ipsum beras merah <br>
                Lorem ipsum beras merah Lorem ipsum beras merah <br>
                Lorem ipsum beras merah Lorem ipsum beras merah
            </p>


        </div>

        {{-- ATUR JUMLAH --}}
        <div class="col-lg-3">
            <div class="border rounded p-3">
                <h6 class="fw-bold mb-3">Atur Jumlah Dan Catatan</h6>

                <!-- JUMLAH + STOK -->
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <!-- Input Group -->
                    <div class="input-group input-group-sm qty-group">
                        <button class="btn qty-btn fw-bold" data-action="minus">-</button>
                        <input type="text" class="form-control text-center fw-semibold qty-input" value="1">
                        <button class="btn qty-btn fw-bold" data-action="plus">+</button>
                    </div>



                    <!-- Stok -->
                    <span class="small fw-medium">
                        Stok Total : sisa 100
                    </span>
                </div>

                <!-- SUBTOTAL -->
                <div class="d-flex justify-content-between fw-bold mb-3">
                    <span class="fw-medium">Subtotal</span>
                    <span>Rp. 100.000</span>
                </div>

                <button class="btn btn-custom-green w-100 mb-2">
                    + Keranjang
                </button>

                <button class="btn btn-custom-outline-green w-100">
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
                @for ($i = 0; $i < 15; $i++)
                    <div class="produk-col">
                        <div class="produk-card rounded">

                            <img src="{{ asset('images/home/category/beras-putih.png') }}" class="img-fluid">

                            <div class="produk-body">
                                <div class="rating">★★★★★</div>

                                <p class="produk-title">
                                    1 Liter Beras Premium<br>
                                    Beras Merah Premium Rojolele
                                </p>

                                <div class="produk-footer">
                                    <span class="harga">Rp. 30.000</span>
                                    <span class="terjual">Tersedia 100</span>
                                </div>
                            </div>

                        </div>
                    </div>
                @endfor
            </div>
        </div>

    </div>

    <script>
        const qtyGroup = document.querySelectorAll('.qty-group');

        qtyGroup.forEach(group => {
            const input = group.querySelector('.qty-input');
            const buttons = group.querySelectorAll('.qty-btn');

            buttons.forEach(btn => {
                btn.addEventListener('click', () => {
                    let current = parseInt(input.value) || 0;

                    if (btn.dataset.action === 'plus') {
                        input.value = current + 1;
                    } else if (btn.dataset.action === 'minus') {
                        input.value = current > 0 ? current - 1 : 0;
                    }
                });
            });
        });
    </script>
@endsection
