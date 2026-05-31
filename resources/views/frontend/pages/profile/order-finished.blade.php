@extends('frontend.pages.profile.account')

@section('title', 'Pesanan Selesai')

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
    </style>

    <div class="order-title mb-3 mt-5">
        Pesanan Selesai
    </div>


    <div class="order-search p-2 rounded mb-3 d-flex align-items-center py-0">
        <span class="iconify me-2" data-icon="majesticons:search-line"></span>
        <input type="text" class="form-control border-0 bg-transparent" placeholder="Cari semua pesanan anda disini">
    </div>


    <div class="case" id="orderList">
    </div>

    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', fetchCompletedOrders);

        async function fetchCompletedOrders() {

            try {

                const token = localStorage.getItem('token');

                const response = await fetch(
                    'http://127.0.0.1:8000/api/orders?status=completed', {
                        headers: {
                            'Accept': 'application/json',
                            'Authorization': `Bearer ${token}`
                        }
                    }
                );

                const result = await response.json();

                const orders = result?.data?.data || [];

                let html = '';

                if (orders.length === 0) {

                    html = `
                <div class="text-center py-5">
                    <h6 class="text-muted">
                        Belum ada pesanan yang selesai
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

                            <span class="badge status-finished text-white top-0 end-0">
                                Selesai
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
                    });
                }

                document.getElementById('orderList').innerHTML = html;

            } catch (error) {

                console.error('ERROR:', error);

                document.getElementById('orderList').innerHTML = `
            <div class="alert alert-danger">
                Gagal memuat data pesanan.
            </div>
        `;
            }
        }
    </script>

@endsection
