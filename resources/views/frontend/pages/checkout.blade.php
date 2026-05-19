<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ami Jaya Jampang</title>

    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>



    <!-- GOOGLE FONT -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"rel="stylesheet">

    <!-- CUSTOM CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/color.css') }}">
    <link rel="stylesheet" href="{{ asset('css/login/login.css') }}">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <link rel="stylesheet" href="{{ asset('css/button.css') }}">


</head>

<body>
    <style>
        .navbar-bg {
            background: linear-gradient(90deg, #0D3523, #1F7D53);
            padding: 10px 0;
            transition: .3s ease;
            z-index: 999;
        }

        .navbar-logo {
            width: 150px;
            height: auto;
        }

        /* Nav Link */
        .navbar .nav-link {
            color: white !important;
            font-weight: 500;
            transition: .3s;
        }

        .navbar .nav-link:hover {
            opacity: .8;
        }

        /* Mobile */
        @media (max-width: 991px) {
            .navbar-collapse {
                background: rgba(13, 53, 35, 0.98);
                margin-top: 12px;
                padding: 15px;
                border-radius: 12px;
            }

            .navbar-nav {
                gap: 10px;
            }

            .navbar-logo {
                width: 120px;
            }
        }

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

        .scroll-items {
            max-height: 420px;
            overflow-y: auto;
        }

        .checkout-items-scroll {
            max-height: 280px;
            overflow-y: auto;
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
    <nav class="navbar navbar-expand-lg navbar-bg fixed-top">
        <div class="container">
            <!-- Logo -->
            <a class="navbar-brand d-flex align-items-center" href="/">
                <img src="{{ asset('images/logo/logo-putih.png') }}" alt="Logo" class="img-fluid navbar-logo">
            </a>
        </div>
    </nav>
    <div class="container mt-5 pt-5">
        <h5 class="fw-bold mb-2 title-checkout py-2">Checkout</h5>

        <div class="row g-4">
            <!-- Kiri: Alamat & Produk -->
            <div class="col-lg-8">
                <!-- Alamat Pengiriman -->
                <div class="card mb-3 p-3">
                    <h6 class="text-custom-gray text-uppercase mb-3 mt-0 fw-semibold">Alamat Pengiriman</h6>
                    <div class="d-flex justify-content-between align-items-start">
                        <!-- Bagian kiri: ikon + nama rumah di atas -->
                        <div id="addressContent">
                            <div class="text-muted">Loading alamat...</div>
                        </div>

                        <!-- Tombol Ganti tetap di kanan -->
                        <button class="btn btn-custom-gray align-self-start mb-0" id="addressActionBtn">
                            ...
                        </button>
                    </div>



                </div>


                <!-- Produk -->
                <div class="card mb-3 p-3">
                    <div class="d-flex gap-3 mb-3">
                        <!-- KANAN: Konten -->
                        <div class="flex-grow-1">
                            <!-- Baris produk -->
                            <div class="mb-2 py-2"
                                style="max-height: 420px; overflow-y: auto; border-bottom: 1.5px solid #B8B9BA;">
                                <div class="checkout-items-scroll">
                                    @foreach ($items as $item)
                                        <div class="card mb-2 p-3">
                                            <div class="d-flex align-items-center gap-3">
                                                <img src="{{ $item->product->image_url }}" class="flex-shrink-0"
                                                    width="100" height="100" style="border-radius: 8px">

                                                <div class="flex-grow-1">
                                                    <p class="mb-0 fw-medium">
                                                        {{ $item->product->name }}
                                                    </p>

                                                    <small class="text-muted">
                                                        {{ $item->quantity }} x
                                                        Rp.{{ number_format($item->price, 0, ',', '.') }}
                                                    </small>
                                                </div>

                                                <div class="text-end flex-shrink-0">
                                                    <p class="mb-0 fw-semibold">
                                                        Rp.{{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>


                            <!-- CARD PENGIRIMAN (sejajar teks produk) -->
                            @php
                                $totalQty = $items->sum('quantity');
                            @endphp
                            <div class="card p-2 mt-3 ms-auto" style="max-width: 92%;">
                                <h6 class="fw-semibold py-2 text-start">Jenis Pengiriman</h6>

                                <div class="card border p-2 mb-2 text-start">

                                    <!-- Antar -->
                                    <div
                                        class="form-check-pengiriman d-flex justify-content-between align-items-center mb-2">
                                        <label class="form-check-label " for="antar">
                                            Di Antar Oleh Penjual Ke Alamat Pembeli
                                        </label>
                                        <input class="form-check-input ms-2" type="radio" name="shipping"
                                            id="antar" value="antar"
                                            {{ $totalQty < 15 ? 'disabled' : 'checked' }}>
                                    </div>

                                    @if ($totalQty < 15)
                                        <small class="text-danger d-block mt-1">
                                            Minimal pembelian 15 item untuk pengiriman antar.
                                        </small>
                                    @endif
                                    <!-- Pickup -->
                                    <div
                                        class="form-check-pengiriman d-flex justify-content-between align-items-center mb-2">
                                        <label class="form-check-label" for="pickup">
                                            Pick Up (Pembeli Ambil Barang Ke Lokasi Penjual)
                                        </label>
                                        <input class="form-check-input ms-2" type="radio" name="shipping"
                                            id="pickup" value="pickup" {{ $totalQty < 15 ? 'checked' : '' }}>
                                    </div>

                                    <div class="d-flex flex-column gap-2 small mb-1">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="iconify" data-icon="bx:map" style="font-size: 20px;"></span>

                                            <span>
                                                Dikirim Dari
                                                <span class="fw-medium">Kabupaten Bogor</span>
                                            </span>
                                        </div>

                                        <div class="d-flex align-items-center gap-2">
                                            <span class="iconify" data-icon="hugeicons:pickup-01"
                                                style="font-size: 20px;"></span>

                                            <span class="fw-medium">
                                                Ongkos Kirim (Rp.25.000–Rp.50.000)
                                            </span>
                                        </div>
                                    </div>

                                    <small class="d-block mb-2 mt-2">
                                        Estimasi Tiba Hari ini – Besok
                                    </small>
                                </div>

                                <div class="note-wrapper position-relative mb-2 text-start">

                                    <!-- Icon -->
                                    <div class="note-icon text-muted">
                                        <span class="iconify" data-icon="streamline:hand-held-tablet-writing"
                                            style="font-size:18px;"></span>
                                    </div>

                                    <!-- Placeholder -->
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
            </div>

            <!-- Kanan: Metode Pembayaran & Ringkasan -->
            <div class="col-lg-4">
                <div class="card p-3">

                    <!-- Metode Pembayaran -->
                    <h6 class="fw-bold mb-2">Metode Pembayaran</h6>
                    <div class="mb-4" id="paymentMethodList">
                        <div class="text-muted small">Loading...</div>
                    </div>


                    <!-- Ringkasan Transaksi (HIJAU, TANPA CARD LAGI) -->
                    <div class="p-3 mb-3 text-white" style="background-color:#198754; border-radius:8px;">
                        <h6 class="fw-bold mb-3">Ringkasan Transaksi Anda</h6>

                        <div class="d-flex justify-content-between mb-1">
                            <span>Total Harga ({{ $items->sum('quantity') }} Barang)</span>
                            <span>Rp.{{ number_format($items->sum(fn($i) => $i->price * $i->quantity), 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span>Total Ongkos Kirim</span>
                            <span id="ongkirDisplay">Rp.0</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Total Lainnya</span>
                            <span>Rp.0</span>
                        </div>
                    </div>

                    <!-- Total -->
                    <div class="pt-3 mt-3" style="border-top:1.5px solid #B8B9BA;">
                        <div class="d-flex justify-content-between align-items-center mb-5">
                            <span>Total Tagihan</span>
                            <span class="fw-bold" id="totalTagihan">
                                Rp.{{ number_format($items->sum(fn($i) => $i->price * $i->quantity), 0, ',', '.') }}
                            </span>
                        </div>

                        <button class="btn btn-main w-100 fw-semibold mt-2" id="btnBayar">
                            Bayar
                        </button>
                    </div>


                </div>
            </div>

        </div>
    </div>


    @include('frontend.components.address-modal')
    @include('frontend.components.address-list-modal')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}">
    </script>
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

            btn.dataset.addressId = address.id;
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

        // =============================================
        //  LOAD PAYMENT METHODS
        // =============================================
        async function loadPaymentMethods() {
            const token = localStorage.getItem('token');
            const paymentImages = {
                'cod': 'cod.png',
                'bca_va': 'bca.png',
                'bni_va': 'bni.png',
                'bri_va': 'bri.png',
                'mandiri_va': 'mandiri.png',
                'qris': 'qris.jpg'
            };

            try {
                const res = await fetch('/api/payment-methods', {
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    }
                });

                const json = await res.json();
                const methods = json.data || [];

                const container = document.getElementById('paymentMethodList');
                container.innerHTML = '';

                methods.forEach((method, index) => {
                    const div = document.createElement('div');
                    const image = paymentImages[method.code] || 'images/payments/bank/default.png';
                    div.className = 'form-check-payment d-flex justify-content-between align-items-center';

                    div.innerHTML = `
                    <label class="d-flex align-items-center gap-2 mb-0" for="pay_${method.code}">
                        <div class="payment-logo">
                        <img
                    src="/images/payments/bank/${image}"
                    alt="${method.name}"
                    style="width:40px; height:auto; object-fit:contain;"
                >
                        </div>

                        <span>${method.name}</span>
                    </label>

                    <input
                        class="form-check-input"
                        type="radio"
                        name="payment"
                        id="pay_${method.code}"
                        value="${method.id}"
                        ${index === 0 ? 'checked' : ''}
                    >
                `;

                    container.appendChild(div);
                });

            } catch (e) {
                console.error('Gagal load payment methods:', e);
            }
        }



        // =============================================
        //  SUBMIT ORDER + HIT SNAP TOKEN
        // =============================================
        async function submitOrder() {
            const token = localStorage.getItem('token');

            const addressId = document.getElementById('addressActionBtn').dataset.addressId;
            if (!addressId) {
                alert('Pilih alamat pengiriman terlebih dahulu');
                return;
            }

            const selectedPayment = document.querySelector('input[name="payment"]:checked');
            if (!selectedPayment) {
                alert('Pilih metode pembayaran terlebih dahulu');
                return;
            }

            const selectedDelivery = document.querySelector('input[name="shipping"]:checked');
            if (!selectedDelivery) {
                alert('Pilih jenis pengiriman terlebih dahulu');
                return;
            }

            const note = document.querySelector('.note-textarea')?.value?.trim() || '';

            const urlParams = new URLSearchParams(window.location.search);
            const itemIds = JSON.parse(urlParams.get('items') || '[]');

            const payload = {
                address_id: parseInt(addressId),
                payment_method_id: parseInt(selectedPayment.value),
                delivery_method: selectedDelivery.value === 'antar' ? 'delivery' : 'pickup',
                note: note,
                item_ids: itemIds
            };

            const btn = document.getElementById('btnBayar');
            btn.disabled = true;
            btn.innerHTML = `<span class="spinner-border spinner-border-sm me-1"></span> Memproses...`;

            try {
                // ── STEP 1: Buat Order ──────────────────────────
                const orderRes = await fetch('/api/orders', {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(payload)
                });

                const orderJson = await orderRes.json();

                if (!orderRes.ok || !orderJson.success) {
                    alert(orderJson.message || 'Gagal membuat order');
                    return;
                }

                const orderNumber = orderJson.data.order_number;

                // ── STEP 2: Hit Snap Token ──────────────────────
                const orderId = orderJson.data.id; // ← pakai id, bukan order_number

                const snapRes = await fetch(`/api/orders/${orderId}/payment/snap-token`, {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                });

                const snapJson = await snapRes.json();

                if (!snapRes.ok || !snapJson.success) {
                    alert(snapJson.message || 'Gagal membuat token pembayaran');
                    return;
                }

                const {
                    snap_token,
                    client_key,
                    snap_url
                } = snapJson.data;

                // ── STEP 3: Buka Midtrans Snap Popup ───────────
                window.snap.pay(snap_token, {
                    onSuccess: function(result) {
                        window.location.href = `/order-all`;
                    },
                    onPending: function(result) {
                        window.location.href = `/order-all`;
                    },
                    onError: function(result) {
                        alert('Pembayaran gagal. Silakan coba lagi.');
                    },
                    onClose: function() {
                        // user tutup popup tanpa bayar
                        btn.disabled = false;
                        btn.innerHTML = 'Bayar';
                    }
                });

            } catch (e) {
                console.error('Error:', e);
                alert('Terjadi kesalahan. Coba lagi.');
                btn.disabled = false;
                btn.innerHTML = 'Bayar';
            }
        }


        // =============================================
        //  INIT
        // =============================================
        document.addEventListener('DOMContentLoaded', function() {
            loadPaymentMethods();

            document.getElementById('btnBayar').addEventListener('click', submitOrder);
        });
    </script>
    <script>
        // =============================================
        //  HITUNG ONGKIR & TOTAL TAGIHAN
        // =============================================
        function hitungTotal() {
            const subtotal = {{ $items->sum(fn($i) => $i->price * $i->quantity) }};
            const selectedDelivery = document.querySelector('input[name="shipping"]:checked');
            const isAntar = selectedDelivery?.value === 'antar';

            // Sesuaikan ongkir dengan logika backend (per qty)
            const totalQty = {{ $items->sum('quantity') }};
            let ongkir = 0;

            if (isAntar) {
                if (totalQty >= 15 && totalQty <= 30) ongkir = 25000;
                else if (totalQty > 30) ongkir = 50000;
            }

            const total = subtotal + ongkir;

            document.getElementById('ongkirDisplay').textContent =
                'Rp.' + ongkir.toLocaleString('id-ID');

            document.getElementById('totalTagihan').textContent =
                'Rp.' + total.toLocaleString('id-ID');
        }

        // Panggil saat radio shipping berubah
        document.querySelectorAll('input[name="shipping"]').forEach(radio => {
            radio.addEventListener('change', hitungTotal);
        });

        // Panggil saat pertama load
        document.addEventListener('DOMContentLoaded', hitungTotal);
    </script>


</body>

</html>
