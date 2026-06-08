@extends('frontend.pages.profile.account')

@section('title', 'Menunggu Pembayaran')

@section('account-content')

    <style>
        /* ========== RESPONSIVE STYLES ========== */

        /* Tablet (max-width: 768px) */
        @media (max-width: 768px) {
            .order-title {
                font-size: 18px;
                padding: 8px;
                margin-top: 0.5rem !important;
                margin-bottom: 0.5rem !important;
            }

            .order-search input {
                font-size: 13px;
                padding: 8px 0;
            }

            .case {
                padding: 12px;
            }

            .order-card .card-body {
                padding: 60px 16px 16px !important;
            }

            .order-card .badge {
                font-size: 10px;
                padding: 5px 12px;
            }

            .order-product {
                flex-direction: column !important;
                align-items: flex-start !important;
                width: 100%;
            }

            .order-product img {
                width: 70px !important;
                height: 70px !important;
            }

            .order-product>div {
                width: 100%;
            }

            .order-divider {
                border-left: 0 !important;
                margin-top: 16px;
                padding-top: 12px;
                border-top: 1px solid #B8B9BA;
                text-align: left !important;
            }

            .order-card .d-flex.justify-content-between {
                flex-direction: column;
                align-items: flex-start !important;
                gap: 10px;
            }

            .order-card .d-flex.gap-2 {
                width: 100%;
                flex-direction: column;
            }

            .order-card .btn-sm {
                width: 100%;
                font-size: 12px;
                padding: 8px 12px;
            }

            .va-number {
                font-size: 12px;
                word-break: break-all;
                white-space: normal;
            }

            .scroll-items {
                max-height: 200px;
            }

            .order-meta {
                font-size: 11px;
            }

            .order-card strong {
                font-size: 13px;
            }

            .ps-4 {
                padding-left: 1rem !important;
            }

            .pt-2 {
                padding-top: 0.5rem !important;
            }
        }

        /* Mobile (max-width: 576px) */
        @media (max-width: 576px) {
            .order-title {
                font-size: 16px;
                padding: 8px;
            }

            .case {
                padding: 8px;
            }

            .order-card .card-body {
                padding: 55px 12px 12px !important;
            }

            .order-card .badge {
                font-size: 9px;
                padding: 4px 10px;
            }

            .order-product img {
                width: 60px !important;
                height: 60px !important;
            }

            .order-product {
                gap: 0.75rem !important;
            }

            .order-product>div strong {
                font-size: 12px;
            }

            .order-meta {
                font-size: 10px;
            }

            .order-card strong {
                font-size: 12px;
            }

            .btn-sm {
                font-size: 11px !important;
                padding: 6px 10px !important;
            }

            .va-number {
                font-size: 11px;
            }

            .scroll-items {
                max-height: 180px;
            }

            .gap-3 {
                gap: 0.75rem !important;
            }

            .mb-5 {
                margin-bottom: 1rem !important;
            }

            .ps-4 {
                padding-left: 0.75rem !important;
            }
        }

        /* Desktop (min-width: 769px) */
        @media (min-width: 769px) {
            .order-divider {
                border-left: 1px solid #B8B9BA;
            }

            .order-product {
                flex-wrap: nowrap !important;
                align-items: center !important;
            }

            .order-product>div {
                flex-shrink: 0;
            }

            .order-card .d-flex.gap-2 {
                flex-direction: row;
            }

            .order-card .btn-sm {
                width: auto;
            }
        }

        /* Large Desktop (min-width: 1200px) */
        @media (min-width: 1200px) {
            .order-product img {
                width: 100px !important;
                height: 100px !important;
            }

            .case {
                padding: 20px;
            }
        }
    </style>

    <div class="order-title mb-3 mt-5">
        Menunggu Pembayaran
    </div>


    <div class="order-search p-2 rounded mb-3 d-flex align-items-center py-0">
        <span class="iconify me-2" data-icon="majesticons:search-line"></span>
        <input type="text" class="form-control border-0 bg-transparent" placeholder="Cari semua pesanan anda disini">
    </div>


    <div class="case" id="orderList"></div>

    @include('frontend.components.payment-guide-modal')
    @include('frontend.components.transaction-detail-modal')
    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
    <script>
        async function loadWaitingOrders() {

            const token = localStorage.getItem('token');

            try {

                const res = await fetch('/api/orders?status=awaiting_payment', {
                    headers: {
                        Authorization: `Bearer ${token}`,
                        Accept: 'application/json'
                    }
                });

                const json = await res.json();

                const orders = json.data.data || [];

                const container = document.getElementById('orderList');

                container.innerHTML = '';

                if (orders.length === 0) {

                    container.innerHTML = `
                    <div class="text-center py-5">
                        Belum ada pesanan menunggu pembayaran
                    </div>
                `;

                    return;
                }

                for (const order of orders) {

                    let paymentDetail = null;

                    /*
                    |--------------------------------------------------------------------------
                    | FETCH PAYMENT DETAIL
                    |--------------------------------------------------------------------------
                    */
                    try {

                        const paymentResponse = await fetch(
                            `/api/orders/${order.id}/payment`, {
                                headers: {
                                    Accept: 'application/json',
                                    Authorization: `Bearer ${token}`
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

                    container.innerHTML += `
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

                    <div class="d-flex align-items-center gap-4 order-product flex-nowrap">

                        <img
                            src="${getPaymentImage(order.payment_method)}"
                            style="width:100px; height:100px; flex-shrink:0; object-fit:contain; background:#f5f5f5;"
                        >

                        <div style="min-width:140px;">
                            <div class="order-meta text-neutral-custom">
                                Metode Pembayaran
                            </div>

                            <strong class="small">
                                ${order.payment_method || '-'}
                            </strong>
                        </div>

                        <div class="flex-grow-1">
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

                   <button
                        class="btn btn-second btn-sm btn-payment-guide"
                        data-bank="${order.payment_method}"
                        data-va="${
                            paymentDetail?.virtual_account_number
                                ?.split(': ')
                                ?.pop() || '-'
                        }"
                        data-total="${order.total_format}">
                        Cara Pembayaran
                    </button>

                    <button
                        class="btn btn-main btn-sm btn-transaction-detail"
                        data-id="${order.id}">
                        Lihat Detail
                    </button>

                </div>

            </div>

        </div>
    </div>
    `;
                }

            } catch (e) {

                console.error(e);

            }
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

        function getPaymentCode(name) {
            const map = {
                'Cash On Delivery (COD)': 'cod',
                'BCA Virtual Account': 'bca_va',
                'BNI Virtual Account': 'bni_va',
                'BRI Virtual Account': 'bri_va',
                'Mandiri Virtual Account': 'mandiri_va',
                'QRIS': 'qris'
            };

            return map[name] || 'bca_va';
        }
        document.addEventListener('DOMContentLoaded', loadWaitingOrders);
    </script>
@endsection
