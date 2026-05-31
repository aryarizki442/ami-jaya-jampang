@extends('frontend.pages.profile.account')

@section('title', 'Semua Pesanan')

@section('account-content')

    <style>
        .order-title {
            background: #2a7b4f;
            color: white;
            text-align: center;
            padding: 10px;
            font-weight: 600;
        }

        .order-search {
            background: #E8E8E9;
            border: 1px solid #e5e5e5;
        }

        .order-card {
            border-radius: 16px;
            border: 1px solid #B8B9BA;
            overflow: hidden;
        }

        .order-card .card-body {
            padding: 30px 24px 24px;
        }

        .order-product {
            flex-wrap: nowrap;
            align-items: flex-start;
        }

        .order-product>div {
            min-width: 0;
            flex-shrink: 0;
        }

        .order-product img {
            width: 100px;
            height: 100px;
            object-fit: contain;
            /* gambar utuh semua */
            border-radius: 12px;
            background-color: #fff;
            display: block;
        }

        .order-divider {
            border-left: 1px solid #B8B9BA;
        }

        .case {
            background: #fff;
            padding: 20px;
        }

        .va-number {
            max-width: 100%;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            display: block;
        }

        .scroll-items {
            max-height: 116px;
            overflow-y: auto;
            padding-right: 5px;
        }

        /* Optional: custom scrollbar agar lebih bagus */
        .scroll-items::-webkit-scrollbar {
            width: 6px;
        }

        .scroll-items::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .scroll-items::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 10px;
        }

        .scroll-items::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        @media (max-width: 768px) {

            .order-card .card-body {
                padding: 70px 18px 18px;
            }

            .order-product {
                flex-direction: column;
                align-items: flex-start !important;
            }

            .order-divider {
                border-left: 0;
                margin-top: 20px;
                text-align: left !important;
            }

            .status-process {
                padding: 8px 14px;
                font-size: 11px;
            }

        }
    </style>


    <div class="order-title mb-3 mt-4">
        Semua Pesanan
    </div>


    <div class="order-search p-2 rounded mb-3 d-flex align-items-center py-0">
        <span class="iconify me-2" data-icon="majesticons:search-line"></span>
        <input type="text" class="form-control border-0 bg-transparent" placeholder="Cari semua pesanan anda disini">
    </div>


    <div class="case" id="ordersContainer">
        {{--
        <div class="card order-card mb-3 position-relative py-0">
            <div class="d-flex justify-content-between align-items-start">

                <div class="ps-4 pt-2">
                    <strong>Pembelian</strong>
                    <span class="order-meta ms-2 text-neutral-custom">27 Januari 2026</span>
                </div>

                <span class="badge status-shipped text-white top-0 end-0">
                    Dikirim
                </span>

            </div>
            <div class="card-body">

                <div class="row align-items-center mb-5">

                    <div class="col-md-8">

                        <div class="d-flex align-items-center gap-3 order-product">

                            <img src="{{ asset('images/home/category/beras-medium.png') }}" style="width:100px;">

                            <div>
                                <strong>Beras Putih Premium</strong>
                                <div class="order-meta text-neutral-custom">x 1</div>
                            </div>

                        </div>

                    </div>

                    <div class="col-md-4 order-divider d-flex flex-column justify-content-center text-end">

                        <div class="order-meta text-neutral-custom">Total Pembayaran</div>
                        <strong>Rp.153.000</strong>

                    </div>

                </div>

                <div class="d-flex justify-content-end gap-2">

                    <button class="btn btn-second btn-sm">
                        Hubungi Penjual
                    </button>

                    <button class="btn btn-main btn-sm px-3">
                        Selesai
                    </button>

                </div>

            </div>
        </div>

        <div class="card order-card mb-3 position-relative py-0">

            <!-- Badge -->

            <div class="d-flex justify-content-between align-items-start">

                <div class="ps-4 pt-2">
                    <strong>Pembelian</strong>
                    <span class="order-meta ms-2 text-neutral-custom">27 Januari 2026</span>
                </div>

                <span class="badge status-finished text-white top-0 end-0">
                    Selesai
                </span>

            </div>
            <div class="card-body">

                <div class="row align-items-center mb-5">

                    <div class="col-md-8">

                        <div class="d-flex align-items-center gap-3 order-product">

                            <img src="{{ asset('images/home/category/beras-medium.png') }}" style="width:100px;">

                            <div>
                                <strong>Beras Putih Premium</strong>
                                <div class="order-meta text-neutral-custom">x 1</div>
                            </div>

                        </div>

                    </div>

                    <div class="col-md-4 order-divider d-flex flex-column justify-content-center text-end">

                        <div class="order-meta text-neutral-custom">Total Pembayaran</div>
                        <strong>Rp.153.000</strong>

                    </div>

                </div>

                <div class="d-flex justify-content-end gap-2">

                    <button class="btn btn-second btn-sm">
                        Detail Transaksi
                    </button>

                    <button class="btn btn-main btn-sm px-3">
                        Nilai
                    </button>

                </div>

            </div>
        </div>

        <div class="card order-card mb-3 position-relative py-0">

            <!-- Badge -->

            <div class="d-flex justify-content-between align-items-start">

                <div class="ps-4 pt-2">
                    <strong>Pembelian</strong>
                    <span class="order-meta ms-2 text-neutral-custom">27 Januari 2026</span>
                </div>

                <span class="badge status-cancelled text-white top-0 end-0">
                    Dibatalkan
                </span>

            </div>
            <div class="card-body">

                <div class="row align-items-center mb-5">

                    <div class="col-md-8">

                        <div class="d-flex align-items-center gap-3 order-product">

                            <img src="{{ asset('images/home/category/beras-medium.png') }}" style="width:100px;">

                            <div>
                                <strong>Beras Putih Premium</strong>
                                <div class="order-meta text-neutral-custom">x 1</div>
                            </div>

                        </div>

                    </div>

                    <div class="col-md-4 order-divider d-flex flex-column justify-content-center text-end">

                        <div class="order-meta text-neutral-custom">Total Pembayaran</div>
                        <strong>Rp.153.000</strong>

                    </div>

                </div>

                <div class="d-flex justify-content-end gap-2">

                    <button class="btn btn-second btn-sm">
                        Rincian Pembatalan
                    </button>

                    <button class="btn btn-main btn-sm px-3">
                        Beli Lagi
                    </button>

                </div>

            </div>
        </div> --}}
    </div>

    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
    <script>
        async function fetchOrders() {

            try {

                const token = localStorage.getItem('token');

                const response = await fetch('http://127.0.0.1:8000/api/orders', {
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${token}`
                    }
                });

                const result = await response.json();

                console.log('ORDERS:', result);

                const orders = result?.data?.data ?? [];

                let html = '';

                // kalau kosong
                if (orders.length === 0) {
                    html = `
        <div class="d-flex flex-column justify-content-center align-items-center text-center py-5" style="min-height: 300px;">
            <span class="iconify"
                data-icon="streamline-ultimate-color:shopping-bag-carry"
                style="font-size:80px; filter: grayscale(1) brightness(1.2);">
            </span>

            <h6 class="fw-semibold mt-3">
                Anda Belum Ada Pesanan
            </h6>
        </div>
    `;
                } else {
                    for (const order of orders) {

                        console.log('ORDER:', order);

                        let paymentDetail = null;


                        /*
                        |--------------------------------------------------------------------------
                        | FETCH PAYMENT DETAIL
                        |--------------------------------------------------------------------------
                        */
                        try {

                            const paymentResponse = await fetch(
                                `http://127.0.0.1:8000/api/orders/${order.id}/payment`, {
                                    headers: {
                                        'Accept': 'application/json',
                                        'Authorization': `Bearer ${token}`
                                    }
                                }
                            );

                            const paymentResult = await paymentResponse.json();

                            console.log('PAYMENT DETAIL:', paymentResult);

                            if (paymentResult.success) {
                                paymentDetail = paymentResult.data;
                            }

                        } catch (err) {

                            console.log('PAYMENT ERROR:', err);

                        }

                        /*
                        |--------------------------------------------------------------------------
                        | AWAITING PAYMENT
                        |--------------------------------------------------------------------------
                        */
                        if (order.status === 'awaiting_payment') {

                            html += `
                    <div class="card order-card mb-3 position-relative">

                        <span class="badge status-waiting text-white position-absolute top-0 end-0">
                            Menunggu Pembayaran
                        </span>

                        <div class="card-body">

                            <div class="d-flex justify-content-between align-items-center mb-0">

                                <div class="order-meta gap-3 d-flex align-items-center text-neutral-custom">
                                    Bayar Sebelum

                                    <span class="text-warning">
                                        <span class="iconify" data-icon="iconoir:clock-solid"></span>

                                     ${
                                            paymentDetail?.expired_at
                                                ? formatDate(paymentDetail.expired_at)
                                                : '-'
                                        }
                                    </span>
                                </div>

                            </div>

                            <div class="row align-items-center mb-5">

                                <div class="col-md-8">

                                    <div class="d-flex align-items-center gap-4 order-product">

                                        <img src="${getPaymentImage(order.payment_method)}"
                                        style="width:100px; height:100px; flex-shrink:0; object-fit:contain; background:#f5f5f5;">

                                        <div>
                                            <div class="order-meta text-neutral-custom">
                                                Metode Pembayaran
                                            </div>

                                            <strong class="small">
                                                ${order.payment_method || '-'}
                                            </strong>
                                        </div>

                                        <div>
                                            <div class="order-meta text-neutral-custom">
                                                Nomor Virtual Account
                                            </div>

                                           <strong class="va-number">
                                            ${
                                                paymentDetail?.virtual_account_number
                                                    ?.split(': ')
                                                    ?.pop() || '-'
                                            }
                                        </strong>
                                        </div>

                                    </div>

                                </div>

                                <div class="col-md-4 order-divider d-flex flex-column justify-content-center text-end">

                                    <div class="order-meta text-neutral-custom">
                                        Total Pembayaran
                                    </div>

                                    <strong>
                                        ${order.total_format || '-'}
                                    </strong>

                                </div>

                            </div>

                            <div class="d-flex justify-content-between align-items-center">

                                <div class="d-flex align-items-center gap-2">

                                    <strong>Pembelian</strong>

                                    <span class="order-meta ms-2 text-neutral-custom">
                                        ${order.created_at}
                                    </span>

                                </div>

                                <div class="d-flex gap-2">

                                    <button class="btn btn-second btn-sm">
                                        Cara Pembayaran
                                    </button>

                                    <button class="btn btn-main btn-sm">
                                        Lihat Detail
                                    </button>

                                </div>

                            </div>

                        </div>
                    </div>
                    `;
                        }

                        /*
                        |--------------------------------------------------------------------------
                        | PAID
                        |--------------------------------------------------------------------------
                        */
                        else if (order.status === 'paid') {

                            // Buat daftar semua item dalam bentuk HTML
                            let allItemsHtml = '';
                            (order.items || []).forEach(item => {
                                allItemsHtml += `
            <div class="d-flex align-items-center gap-3 order-product mb-3">
                <img src="${item.image}" style="width:100px;">
                <div>
                    <strong>${item.name}</strong>
                    <div class="order-meta text-neutral-custom">
                        x ${item.quantity || 1}
                    </div>
                </div>
            </div>
        `;
                            });

                            html += `
        <div class="card order-card mb-3 position-relative py-0">

            <div class="d-flex justify-content-between align-items-start">

                <div class="ps-4 pt-2">
                    <strong>Pembelian</strong>

                    <span class="order-meta ms-2 text-neutral-custom">
                        ${order.created_at}
                    </span>
                </div>

                <span class="badge status-process text-white top-0 end-0">
                    ${order.status === 'paid' ? 'Diproses' : order.status_label}
                </span>

            </div>

            <div class="card-body">

                <!-- 1. GAMBAR 1: row align-items-center → align-items-start -->
                <div class="row align-items-start mb-5">

                    <!-- 2. GAMBAR 2: tambahkan div scroll di sini -->
                    <div class="col-md-8">
                        <div class="scroll-items">
                            ${allItemsHtml}
                        </div>
                    </div>

                    <div class="col-md-4 order-divider d-flex flex-column justify-content-center text-end">

                        <div class="order-meta text-neutral-custom">
                            Total Pembayaran
                        </div>

                        <strong>
                            ${order.total_format}
                        </strong>

                    </div>

                </div>

                <div class="d-flex justify-content-end gap-2">

                    <button class="btn btn-second btn-sm">
                        Hubungi Penjual
                    </button>

                </div>

            </div>

        </div>
    `;
                        } else if (order.status === 'shipped') {
                            console.log(JSON.stringify(order, null, 2));
                            console.log('DELIVERY METHOD:', order.delivery_method);
                            console.log(
                                'Order:',
                                order.id,
                                'Status:',
                                order.status,
                                'Delivery:',
                                order.delivery_method
                            );
                            let allItemsHtml = '';

                            (order.items || []).forEach(item => {
                                allItemsHtml += `
            <div class="d-flex align-items-center gap-3 order-product mb-3">
                <img src="${item.image}" style="width:100px;">
                <div>
                    <strong>${item.name}</strong>
                    <div class="order-meta text-neutral-custom">
                        x ${item.quantity}
                    </div>
                </div>
            </div>
        `;
                            });

                            html += `
        <div class="card order-card mb-3 position-relative py-0">

            <div class="d-flex justify-content-between align-items-start">

                <div class="ps-4 pt-2">
                    <strong>Pembelian</strong>
                    <span class="order-meta ms-2 text-neutral-custom">
                        ${order.created_at}
                    </span>
                </div>

                <span class="badge status-shipped  ${
                order.delivery_method === 'pickup'
                    ? 'status-pickup'
                    : 'status-shipped'
            } text-white top-0 end-0">
                ${mapStatusLabel(order.status, order.delivery_method)}
            </span>
            </div>

            <div class="card-body">

                <div class="row align-items-start mb-5">

                     <div class="col-md-8">
                        <div class="scroll-items">
                            ${allItemsHtml}
                        </div>
                    </div>

                    <div class="col-md-4 order-divider text-end">
                        <div class="order-meta text-neutral-custom">
                            Total Pembayaran
                        </div>
                        <strong>${order.total_format}</strong>
                    </div>

                </div>

                <div class="d-flex justify-content-end gap-2">

                    <button class="btn btn-second btn-sm">
                        Hubungi Penjual
                    </button>

                    <button class="btn btn-main btn-sm btn-complete"
                            data-id="${order.id}">
                        Selesai
                    </button>

                </div>

            </div>

        </div>
    `;
                        } else if (order.status === 'completed') {

                            let allItemsHtml = '';

                            (order.items || []).forEach(item => {
                                allItemsHtml += `
            <div class="d-flex align-items-center gap-3 order-product mb-3">
                <img src="${item.image}" style="width:100px;">
                <div>
                    <strong>${item.name}</strong>
                    <div>x ${item.quantity}</div>
                </div>
            </div>
        `;
                            });

                            html += `
        <div class="card order-card mb-3 position-relative py-0">

            <div class="d-flex justify-content-between align-items-start">

                <div class="ps-4 pt-2">
                    <strong>Pembelian</strong>
                    <span class="order-meta ms-2 text-neutral-custom">
                        ${order.created_at}
                    </span>
                </div>

                <span class="badge status-finished text-white top-0 end-0">
                    Selesai
                </span>

            </div>

            <div class="card-body">

                <div class="row align-items-start mb-5">

                     <div class="col-md-8">
                        <div class="scroll-items">
                            ${allItemsHtml}
                        </div>
                    </div>

                    <div class="col-md-4 order-divider text-end">
                        <div class="order-meta text-neutral-custom">
                            Total Pembayaran
                        </div>
                        <strong>${order.total_format}</strong>
                    </div>

                </div>

                <div class="d-flex justify-content-end gap-2">

                    <button class="btn btn-second btn-sm">
                        Detail Transaksi
                    </button>

                    <button class="btn btn-main btn-sm">
                        Nilai
                    </button>

                </div>

            </div>

        </div>
    `;
                        } else if (order.status === 'cancelled') {

                            let allItemsHtml = '';

                            (order.items || []).forEach(item => {
                                allItemsHtml += `
            <div class="d-flex align-items-center gap-3 order-product mb-3">
                <img src="${item.image}" style="width:100px;">
                <div>
                    <strong>${item.name}</strong>
                    <div>x ${item.quantity}</div>
                </div>
            </div>
        `;
                            });

                            html += `
        <div class="card order-card mb-3 position-relative py-0">

            <div class="d-flex justify-content-between align-items-start">

                <div class="ps-4 pt-2">
                    <strong>Pembelian</strong>
                    <span class="order-meta ms-2 text-neutral-custom">
                        ${order.created_at}
                    </span>
                </div>

                <span class="badge status-cancelled text-white top-0 end-0">
                    Dibatalkan
                </span>

            </div>

            <div class="card-body">

                <div class="row align-items-start mb-5">

                    <div class="col-md-8">
                        <div class="scroll-items">
                            ${allItemsHtml}
                        </div>
                    </div>

                    <div class="col-md-4 order-divider text-end">
                        <div class="order-meta text-neutral-custom">
                            Total Pembayaran
                        </div>
                        <strong>${order.total_format}</strong>
                    </div>

                </div>

                <div class="d-flex justify-content-end gap-2">

                    <button class="btn btn-second btn-sm">
                        Rincian Pembatalan
                    </button>

                    <button class="btn btn-main btn-sm btn-reorder"
                            data-id="${order.id}">
                        Beli Lagi
                    </button>

                </div>

            </div>

        </div>
    `;
                        }
                    }
                }

                document.getElementById('ordersContainer').innerHTML = html;


            } catch (error) {

                console.log('ERROR:', error);

            }
        }

        /*
        |--------------------------------------------------------------------------
        | PAYMENT IMAGE
        |--------------------------------------------------------------------------
        */
        function getPaymentImage(method) {

            if (!method) {
                return '/images/payments/default.png';
            }

            method = method.toLowerCase();

            if (method.includes('cod')) {
                return '/images/payments/bank/cod.png';
            }
            if (method.includes('bca')) {
                return '/images/payments/bank/bca.png';
            }

            if (method.includes('bni')) {
                return '/images/payments/bank/bni.png';
            }

            if (method.includes('bri')) {
                return '/images/payments/bank/bri.png';
            }

            if (method.includes('mandiri')) {
                return '/images/payments/bank/mandiri.png';
            }

            return '/images/payments/default.png';
        }

        function formatDate(dateString) {

            if (!dateString) return '-';

            const date = new Date(dateString);

            return date.toLocaleString('id-ID', {
                day: 'numeric',
                month: 'short',
                hour: '2-digit',
                minute: '2-digit'
            }) + ' WIB';
        }

        function mapStatusLabel(status, deliveryMethod = null) {
            // Mapping untuk paid
            if (status === 'paid') {
                return 'Menunggu Konfirmasi';
            }

            // Mapping untuk shipped (bedakan berdasarkan delivery_method)
            if (status === 'shipped') {
                if (deliveryMethod === 'pickup') {
                    return 'Dijemput';
                }
                return 'Dikirim';
            }

            // Mapping untuk cancelled
            if (status === 'cancelled') {
                return 'Dibatalkan';
            }

            // Mapping untuk rejected (ditolak admin)
            if (status === 'rejected') {
                return 'Ditolak';
            }

            // Status lainnya
            const labels = {
                'awaiting_payment': 'Menunggu Pembayaran',
                'completed': 'Selesai'
            };

            return labels[status] || status;
        }

        fetchOrders();
    </script>
@endsection
