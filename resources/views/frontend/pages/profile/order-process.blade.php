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
    </style>
    <div class="order-title mb-3 mt-5">
        Pesanan Diproses
    </div>


    <div class="order-search p-2 rounded mb-3 d-flex align-items-center py-0">
        <span class="iconify me-2" data-icon="majesticons:search-line"></span>
        <input type="text" class="form-control border-0 bg-transparent" placeholder="Cari semua pesanan anda disini">
    </div>


    <div class="case" id="ordersContainer"></div>

    </div>

    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
    <script>
        async function loadProcessOrders() {

            const token = localStorage.getItem('token');

            if (!token) return;

            try {

                const res = await fetch('/api/orders?status=paid', {
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    }
                });

                const json = await res.json();

                const orders = json.data.data || [];

                const container = document.getElementById('ordersContainer');

                container.innerHTML = '';

                if (orders.length === 0) {

                    container.innerHTML = `
                <div class="text-center py-5">
                    Belum ada pesanan diproses
                </div>
            `;

                    return;
                }

                orders.forEach(order => {
                    let allItemsHtml = '';

                    (order.items || []).forEach(item => {
                        allItemsHtml += `
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
                    container.innerHTML += `
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

                    <div class="row align-items-center mb-5">

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
                });

            } catch (e) {

                console.error(e);

            }
        }

        function formatDate(dateString) {

            const date = new Date(dateString);

            return date.toLocaleDateString('id-ID', {
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });
        }

        document.addEventListener('DOMContentLoaded', loadProcessOrders);
    </script>
@endsection
