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
        <h5 class="fw-bold title-checkout py-4 mb-0">Checkout</h5>

        <div class="row g-4 ">
            <!-- Kiri: Alamat & Produk -->
            <div class="col-lg-8">
                <!-- Alamat Pengiriman -->
                <div class="card mb-3 p-3" id="addressCard">
                    <h6 class="text-custom-gray text-uppercase mb-3 fw-semibold">
                        Alamat Pengiriman
                    </h6>

                    <div class="d-flex justify-content-between align-items-start">

                        <!-- LEFT -->
                        <div id="addressContent">
                            <div class="text-muted">Loading alamat...</div>
                        </div>

                        <!-- RIGHT BUTTON -->
                        <button class="btn btn-custom-gray align-self-start mb-0" id="addressActionBtn">
                            ...
                        </button>

                    </div>
                </div>


                <!-- Produk -->
                @foreach ($items as $item)
                    <div class="card mb-2 p-5 py-4 mt-2">
                        <div class="d-flex gap-3 mb-3">

                            <img src="{{ $item->product->image_url }}" class="rounded-circle flex-shrink-0" width="100"
                                height="100">

                            <div class="flex-grow-1">

                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <p class="mt-4">
                                        {{ $item->product->name }}
                                    </p>

                                    <div class="text-end">
                                        <h6 class="fw-semibold mb-3">
                                            Rp.{{ number_format($item->price, 0, ',', '.') }}
                                        </h6>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                @endforeach

            </div>

            <!-- Kanan: Metode Pembayaran & Ringkasan -->
            <div class="col-lg-4  mb-4 ">
                <div class="card p-3 ">

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

    @include('frontend.components.address-modal')
    @include('frontend.components.address-list-modal')


    <script>
        async function loadUserAddress() {
            const token = localStorage.getItem('token');

            if (!token) {
                renderEmptyAddress();
                return;
            }

            try {
                const res = await fetch('/api/addresses', {
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    }
                });

                if (!res.ok) throw new Error();

                const json = await res.json();
                const addresses = json.data || [];

                if (addresses.length === 0) {
                    renderEmptyAddress();
                    return;
                }

                const primaryAddress = addresses.find(addr => addr.is_primary);

                renderAddress(primaryAddress || addresses[0]);

            } catch (e) {
                renderEmptyAddress();
            }
        }

        function renderEmptyAddress() {
            const content = document.getElementById('addressContent');
            const btn = document.getElementById('addressActionBtn');

            content.innerHTML = `
    <div class="d-flex align-items-center gap-2 mb-2">
        <i class='bx bxs-map' style="font-size: 1.8rem; color: #1F7D53;"></i>

        <div>
            <div class="fw-semibold text-dark">
                Anda belum memiliki alamat
            </div>
        </div>
    </div>
`;
            btn.innerHTML = `
            <span class="iconify" data-icon="ic:round-plus"></span>
            <span>Tambah Alamat</span>
`;

            btn.className = 'btn btn-main align-self-start mb-0';

            btn.onclick = function() {
                openAlamatModal();
            };
        }

        function renderAddress(address) {
            const content = document.getElementById('addressContent');
            const btn = document.getElementById('addressActionBtn');
            const labelText = {
                home: 'Rumah',
                office: 'Kantor'
            };

            const fullAddress = `
        ${address.detail ?? ''},
        ${address.village ?? ''},
        ${address.district},
        ${address.city},
        ${address.province}
        ${address.postal_code ? address.postal_code : ''}
         `;

            content.innerHTML = `
        <div class="d-flex align-items-center gap-2 mb-2">
            <i class='bx bxs-map' style="color: #1F7D53; font-size: 1.8rem;"></i>
            <div class="fw-bold">
              ${labelText[address.label] ?? 'Alamat'} . ${address.recipient_name}
            </div>
        </div>

        <p class="small mb-0">
            ${fullAddress}
            <span class="text-muted">(${address.phone})</span>
        </p>
        `;

            btn.textContent = 'Ganti';
            btn.onclick = function() {
                openAddressListModal();
            };
        }
        document.addEventListener('DOMContentLoaded', function() {
            loadUserAddress();
        });

        function openAddressListModal() {

            // tutup modal alamat kalau masih kebuka
            const alamatModalEl = document.getElementById('alamatModal');

            if (alamatModalEl) {
                const alamatInstance = bootstrap.Modal.getInstance(alamatModalEl);

                if (alamatInstance) {
                    alamatInstance.hide();
                }
            }

            // buka modal list
            const modalEl = document.getElementById('addressListModal');

            if (!modalEl) return;

            loadAddressList();

            const modal = bootstrap.Modal.getOrCreateInstance(modalEl);

            setTimeout(() => {
                modal.show();
            }, 200);
        }
    </script>

@endsection
