@extends('app')

@section('title', 'Checkout')

@section('content')
    <style>
        .title-checkout {
            font-size: 28px;
        }

        .card {
            transition: background 0.2s;
            border: none;
            /* hilangkan border card default */
            box-shadow: none;
            /* hilangkan shadow kalau ada */
            background-color: #fff;
            /* tetap putih */
        }

        /* .card:hover {
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            background: #F6F6F6;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        } */

        .form-check-pengiriman {
            background-color: #fff;
            padding: 10px 0px;
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
            border-bottom: 1.5px solid #B8B9BA;
        }

        .form-check-payment {
            padding: 8px 0;
            border-bottom: 1.5px solid #B8B9BA;
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

        .form-check-input:checked {
            background-color: #fff;
            border-color: #10B500;
            background-image: none;
        }

        .form-check-input:checked::before {
            content: "";
            display: block;
            width: 8px;
            height: 8px;
            margin: 3px auto;
            border-radius: 50%;
            background-color: #10B500;
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

        .note-wrapper {
            width: 100%;
        }

        .note-textarea {
            width: 100%;
            border: 0;
            border-top: 1.5px solid #B8B9BA;
            border-bottom: 1.5px solid #B8B9BA;
            padding: 12px 48px 0px 44px;
            /* kiri BESAR buat icon */
            font-size: 0.875rem;
            resize: none;
            outline: none;
            background: transparent;
        }

        /* ICON FIX DI KIRI */
        .note-icon {
            position: absolute;
            top: 15px;
            left: 12px;
            pointer-events: none;
        }

        /* PLACEHOLDER TEKS */
        .note-placeholder {
            position: absolute;
            top: 15px;
            left: 44px;
            /* setelah icon */
            pointer-events: none;
            transition: opacity 0.2s ease;
        }

        /* COUNTER KANAN */
        .note-counter {
            position: absolute;
            top: 15px;
            right: 12px;
            pointer-events: none;
        }

        .note-textarea:focus {
            border-color: #0d6efd;
        }

        .payment-logo {
            width: 48px;
            /* lebar seragam */
            height: 24px;
            /* tinggi seragam */
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .payment-logo img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            /* JAGA TAJAM, TIDAK STRETCH */
        }

        .payment-logo {
            width: 48px;
            /* lebar seragam */
            height: 24px;
            /* tinggi seragam */
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .payment-logo img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            /* JAGA TAJAM, TIDAK STRETCH */
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
        <h5 class="fw-bold mb-4 title-checkout">Checkout</h5>

        <div class="row g-4">
            <!-- Kiri: Alamat & Produk -->
            <div class="col-lg-8">
                <!-- Alamat Pengiriman -->
                <div class="card mb-3 p-3">
                    <h6 class="text-custom-gray text-uppercase mb-3 fw-semibold">Alamat Pengiriman</h6>
                    <div class="d-flex justify-content-between align-items-start">
                        <!-- Bagian kiri: ikon + nama rumah di atas -->
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <i class='bx bxs-map' style="color: #1F7D53; font-size: 1.8rem;"></i>
                                <div class="fw-bold">Rumah . Malik Hassan</div>
                            </div>
                            <!-- Alamat di bawah kanan -->
                            <p class="small text-start mb-0">
                                Perumahan Cibinong Rt111 Rw222 Cibinong, Kabupaten Bogor,<br>
                                Jawa Barat 16345 <span class="text-muted">(0812002211122)</span>
                            </p>
                        </div>

                        <!-- Tombol Ganti tetap di kanan -->
                        <button class="btn btn-custom-gray align-self-start mb-0">Ganti</button>
                    </div>



                </div>


                <!-- Produk -->
                <div class="card mb-3 p-3">
                    <div class="d-flex gap-3 mb-3">
                        <!-- KIRI: Gambar -->
                        <img src="{{ asset('images/home/category/beras-putih.png') }}" alt="Beras"
                            class="rounded-circle flex-shrink-0" width="100" height="100">

                        <!-- KANAN: Konten -->
                        <div class="flex-grow-1">
                            <!-- Baris produk -->
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <p class="mt-4">1 Karung Beras Putih Premium</p>

                                <div class="text-end">
                                    <h6 class="fw-semibold mb-3">Rp.100.000</h6>
                                    <div class="input-group input-group-sm qty-group">
                                        <button class="btn qty-btn fw-bold" data-action="minus">-</button>
                                        <input type="text" class="form-control text-center fw-semibold qty-input"
                                            value="1">
                                        <button class="btn qty-btn fw-bold" data-action="plus">+</button>
                                    </div>
                                </div>
                            </div>

                            <!-- CARD PENGIRIMAN (sejajar teks produk) -->
                            <h6 class="fw-semibold ">Jenis Pengiriman</h6>
                            <div class="card border p-2 mb-2">

                                <!-- Antar -->
                                <div class="form-check-pengiriman d-flex justify-content-between align-items-center mb-2">
                                    <label class="form-check-label " for="antar">
                                        Di Antar Oleh Penjual Ke Alamat Pembeli
                                    </label>
                                    <input class="form-check-input ms-2" type="radio" name="shipping" id="antar"
                                        checked>
                                </div>

                                <!-- Pickup -->
                                <div class="form-check-pengiriman d-flex justify-content-between align-items-center mb-2">
                                    <label class="form-check-label" for="pickup">
                                        Pick Up (Pembeli Ambil Barang Ke Lokasi Penjual)
                                    </label>
                                    <input class="form-check-input ms-2" type="radio" name="shipping" id="pickup">
                                </div>
                                <div class="d-flex flex-column gap-2 small mb-1">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="iconify" data-icon="bx:map" style="font-size: 20px;">
                                        </span>
                                        <span>
                                            Dikirim Dari
                                            <span class="fw-medium">Kabupaten Bogor</span>
                                        </span>

                                    </div>

                                    <div class="d-flex align-items-center gap-2">
                                        <span class="iconify" data-icon="hugeicons:pickup-01" style="font-size: 20px;">
                                        </span>
                                        <span class="fw-medium">Ongkos Kirim (Rp.25.000–Rp.50.000)</span>
                                    </div>
                                </div>


                                <small class="d-block mb-2 mt-2">
                                    Estimasi Tiba Hari ini – Besok
                                </small>
                            </div>
                            <div class="note-wrapper position-relative mb-2">

                                <!-- Icon (SELALU ADA) -->
                                <div class="note-icon text-muted">
                                    <span class="iconify" data-icon="streamline:hand-held-tablet-writing"
                                        style="font-size:18px;"></span>
                                </div>

                                <!-- Placeholder text -->
                                <div class="note-placeholder text-muted small">
                                    Beri Catatan
                                </div>

                                <!-- Counter -->
                                <div class="note-counter text-muted small">
                                    0/200
                                </div>

                                <textarea class="note-textarea" maxlength="200"
                                    onfocus="this.parentElement.querySelector('.note-placeholder').style.opacity='0'"
                                    onblur="if(!this.value) this.parentElement.querySelector('.note-placeholder').style.opacity='1'"
                                    oninput="this.parentElement.querySelector('.note-counter').innerText = this.value.length + '/200'">
    </textarea>

                                <small class="text-muted fst-italic d-block mt-0">
                                    * Pengiriman Khusus Wilayah Bogor dan Sekitarnya
                                </small>
                            </div>






                        </div>
                    </div>
                </div>

            </div>

            <!-- Kanan: Metode Pembayaran & Ringkasan -->
            <div class="col-lg-4">
                <div class="card p-3">

                    <!-- Metode Pembayaran -->
                    <h6 class="fw-bold mb-2">Metode Pembayaran</h6>

                    <div class="form-check-payment d-flex justify-content-between align-items-center">
                        <label class="d-flex align-items-center gap-2 mb-0" for="cod">
                            <div class="payment-logo">
                                <img src="{{ asset('images/payments/bank/cod.png') }}" alt="COD">
                            </div>
                            <span>Cash On Delivery</span>
                        </label>
                        <input class="form-check-input" type="radio" name="payment" id="cod" checked>
                    </div>

                    <div class="form-check-payment d-flex justify-content-between align-items-center">
                        <label class="d-flex align-items-center gap-2 mb-0" for="bca">
                            <div class="payment-logo">
                                <img src="{{ asset('images/payments/bank/bca.png') }}" alt="BCA">
                            </div>
                            <span>BCA Virtual Account</span>
                        </label>
                        <input class="form-check-input" type="radio" name="payment" id="bca">
                    </div>

                    <div class="form-check-payment d-flex justify-content-between align-items-center">
                        <label class="d-flex align-items-center gap-2 mb-0" for="bni">
                            <div class="payment-logo">
                                <img src="{{ asset('images/payments/bank/bni.png') }}" alt="BNI">
                            </div>
                            <span>BNI Virtual Account</span>
                        </label>
                        <input class="form-check-input" type="radio" name="payment" id="bni">
                    </div>

                    <div class="form-check-payment d-flex justify-content-between align-items-center">
                        <label class="d-flex align-items-center gap-2 mb-0" for="bri">
                            <div class="payment-logo">
                                <img src="{{ asset('images/payments/bank/bri.png') }}" alt="BRI">
                            </div>
                            <span>BRI Virtual Account</span>
                        </label>
                        <input class="form-check-input" type="radio" name="payment" id="bri">
                    </div>

                    <div class="form-check-payment d-flex justify-content-between align-items-center">
                        <label class="d-flex align-items-center gap-2 mb-0" for="mandiri">
                            <div class="payment-logo">
                                <img src="{{ asset('images/payments/bank/mandiri.png') }}" alt="Mandiri">
                            </div>
                            <span>Mandiri Virtual Account</span>
                        </label>
                        <input class="form-check-input" type="radio" name="payment" id="mandiri">
                    </div>

                    <div class="form-check-payment d-flex justify-content-between align-items-center mb-3">
                        <label class="d-flex align-items-center gap-2 mb-0" for="qris">
                            <div class="payment-logo">
                                <img src="{{ asset('images/payments/bank/qris.jpg') }}" alt="QRIS">
                            </div>
                            <span>QRIS</span>
                        </label>
                        <input class="form-check-input" type="radio" name="payment" id="qris">
                    </div>


                    <!-- Ringkasan Transaksi (HIJAU, TANPA CARD LAGI) -->
                    <div class="p-3 mb-3 text-white" style="background-color:#198754; border-radius:8px;">
                        <h6 class="fw-bold mb-3">Ringkasan Transaksi Anda</h6>

                        <div class="d-flex justify-content-between mb-1">
                            <span>Total Harga (1 Barang)</span>
                            <span>Rp.100.000</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span>Total Ongkos Kirim</span>
                            <span>Rp.50.000</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Total Lainnya</span>
                            <span>Rp.3.000</span>
                        </div>
                    </div>

                    <!-- Total -->
                    <div class="pt-3 mt-3" style="border-top:1.5px solid #B8B9BA;">
                        <div class="d-flex justify-content-between align-items-center mb-5">
                            <span>Total Tagihan</span>
                            <span class="fw-bold">Rp.153.000</span>
                        </div>

                        <button class="btn btn-custom-green w-100 fw-semibold mt-2">
                            Bayar
                        </button>
                    </div>


                </div>
            </div>

        </div>
    </section>

@endsection
