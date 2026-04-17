@extends('app')

@section('title', 'Keranjang Belanja')

@section('content')

    <style>
        .title-cart {
            font-size: 28px;
        }

        .cart-item,
        .card {
            transition: background 0.2s;
            border: none;
            /* hilangkan border card default */
            box-shadow: none;
            /* hilangkan shadow kalau ada */
            background-color: #fff;
            /* tetap putih */
        }

        .cart-item:hover {
            background: #F6F6F6;
            /* hover tetap ada */
        }

        .custom-border {
            border-bottom: 1px solid #000000;
        }

        .form-check {
            background-color: #fff;
            /* sama seperti card */
            border: 1px solid #F6F6F6;
            /* border mirip cart-item */
            border-radius: 5px;
            /* rounded sama */
            padding: 20px 47px;
            /* beri ruang dalam */
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
        }

        /* hapus efek biru saat fokus */
        .form-check-input:focus {
            outline: none;
            box-shadow: none;
        }

        /* checkbox hijau tetap */
        .form-check-input:checked {
            background-color: #1F7D53;
            border-color: #1F7D53;
            accent-color: #1F7D53;
        }

        .form-check-input:checked::after {
            background-color: #fff;
            border-color: #1F7D53;
        }

        .qty-group {
            border: 1px solid #adadad;
            border-radius: 5px;
            display: flex;
            align-items: center;
            overflow: hidden;

            /* responsif: lebar menyesuaikan parent tapi max 100px */
            width: 100%;
            max-width: 100px;
            height: 32px;
        }

        .qty-group .qty-btn {
            background-color: #fff;
            border: none;
            color: #555;
            height: 100%;
            padding: 0 6px;
            /* lebih fleksibel di mobile */
            font-size: 16px;
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
            font-size: 14px;
            padding: 0;
            flex-grow: 1;
            /* input akan menyesuaikan sisa ruang */
        }

        .cart-scroll {
            max-height: 650px;
            /* ± 5 card */
            overflow-y: auto;
            padding-right: 5px;
        }


        /* OPTIONAL: Ukuran lebih kecil di layar mobile */
        @media (max-width: 576px) {
            .qty-group {
                max-width: 80px;
                /* lebih kecil di mobile */
                height: 28px;
            }

            .qty-group .qty-btn {
                font-size: 14px;
                padding: 0 4px;
            }

            .qty-group .form-control {
                font-size: 12px;
            }
        }
    </style>
    <section>
        <h5 class="fw-bold mb-4 title-cart">Keranjang</h5>

        <div class="row d-flex align-items-start g-4">
            <!-- List Keranjang -->
            <div class="col-md-8">

                <!-- INI TETAP (TIDAK IKUT SCROLL) -->
                <div class="form-check mb-2 mt-2">
                    <label class="form-check-label" for="selectAll">
                        <input class="form-check-input" type="checkbox" id="selectAll">
                        Pilih Semua
                    </label>
                </div>

                <!-- YANG DI SCROLL HANYA INI -->
                <div class="cart-scroll">
                    @for ($i = 0; $i < 8; $i++)
                        <div class="card mb-2 p-4 d-flex flex-row align-items-center cart-item">
                            <input class="form-check-input me-3" type="checkbox">
                            <img src="{{ asset('images/home/category/beras-putih.png') }}" alt="Beras"
                                class="rounded-circle me-3" width="100" height="100">
                            <div class="flex-grow-1">
                                <p class="mb-0">Beras Merah Rojo Lele</p>
                            </div>
                            <div class="text-end">
                                <h5 class="mb-4 fw-semibold">Rp.100.000</h5>
                                <div class="d-flex align-items-center justify-content-end">
                                    <button class="btn btn-lg me-2">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                    <div class="input-group input-group-sm qty-group">
                                        <button class="btn qty-btn fw-bold" data-action="minus">-</button>
                                        <input type="text" class="form-control text-center fw-semibold qty-input"
                                            value="1">
                                        <button class="btn qty-btn fw-bold" data-action="plus">+</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endfor
                </div>

            </div>

            <!-- Ringkasan Belanja -->
            <div class="col-md-4 d-flex">
                <div class="card p-4 w-100">
                    <h6>Ringkasan Belanja</h6>

                    <!-- Total dengan border bawah -->
                    <div class="d-flex justify-content-between align-items-center custom-border pb-4 mb-3 mt-3">
                        <span class="text-muted">Total</span>
                        <span class="fw-bold" id="totalAmount">Rp.100.000</span>
                    </div>
                    <button class="btn btn-custom-green w-100">Beli</button>
                </div>
            </div>
            {{-- Rekomendasi Untukmu --}}
            <div class="mt-5">
                <h6 class="fw-bold text-custom-green mb-3">Rekomendasi Untukmu</h6>

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
                                        <button class="btn btn-second w-100 mb-2">
                                            + Keranjang
                                        </button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    @endfor
                </div>
    </section>


    <script>
        document.querySelectorAll('.quantity-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const input = this.parentElement.querySelector('.qty-input');
                let value = parseInt(input.value);
                if (this.dataset.action === 'increase') value++;
                else if (this.dataset.action === 'decrease' && value > 1) value--;
                input.value = value;
                updateTotal();
            });
        });

        function updateTotal() {
            let total = 0;
            document.querySelectorAll('.cart-item').forEach(item => {
                const qty = parseInt(item.querySelector('.qty-input').value);
                const priceText = item.querySelector('p.fw-bold').innerText.replace('Rp.', '').replace(/\./g, '');
                total += qty * parseInt(priceText);
            });
            document.getElementById('totalAmount').innerText = 'Rp.' + total.toLocaleString();
        }

        updateTotal();
    </script>
@endsection
