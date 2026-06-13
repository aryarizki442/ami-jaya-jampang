@extends('frontend.pages.profile.account')

@section('title', 'Pesanan Dikirim')

@section('account-content')
    <style>
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
        Pesanan Dikirim
    </div>


    <div class="order-search p-2 rounded mb-3 d-flex align-items-center py-0">
        <span class="iconify me-2" data-icon="majesticons:search-line"></span>
        <input type="text" class="form-control border-0 bg-transparent" placeholder="Cari semua pesanan anda disini">
    </div>


    <div class="case">

    </div>

    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', fetchShippedOrders);

        async function fetchShippedOrders() {

            try {

                const token = localStorage.getItem('token');

                const response = await fetch('http://127.0.0.1:8000/api/orders', {
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${token}`
                    }
                });

                const result = await response.json();

                const orders = (result?.data?.data || [])
                    .filter(order => order.status === 'shipped');

                let html = '';

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

                    orders.forEach(order => {

                        let itemsHtml = '';

                        (order.items || []).forEach(item => {

                            itemsHtml += `
                        <div class="d-flex align-items-center gap-3 order-product mb-3">

                            <img src="${item.image}"
                                 style="width:100px;height:100px;object-fit:cover;">

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

                            <span class="badge status-shipped text-white top-0 end-0">
                                ${
                                    order.delivery_method === 'pickup'
                                        ? 'Dijemput'
                                        : 'Dikirim'
                                }
                            </span>

                        </div>

                        <div class="card-body">

                            <div class="row align-items-start mb-5">

                               <div class="col-md-8">

                                    <div class="scroll-items">
                                        ${itemsHtml}
                                    </div>

                                </div>

                                <div class="col-md-4 order-divider text-end">

                                    <div class="order-meta text-neutral-custom">
                                        Total Pembayaran
                                    </div>

                                    <strong>
                                        ${order.total_format}
                                    </strong>

                                </div>

                            </div>

                            <div class="d-flex justify-content-end gap-2">

                                <button
                                    class="btn btn-second btn-sm"
                                    onclick="window.open('https://wa.me/6281211223344', '_blank')">
                                    Hubungi Penjual
                                </button>
                            </div>

                        </div>

                    </div>
                `;
                    });
                }

                document.querySelector('.case').innerHTML = html;

            } catch (error) {

                console.error(error);

            }
        }
    </script>

@endsection
