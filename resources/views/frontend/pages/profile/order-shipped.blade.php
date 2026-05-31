@extends('frontend.pages.profile.account')

@section('title', 'Pesanan Dikirim')

@section('account-content')

    <div class="order-title mb-3 mt-5">
        Pesanan Dikirim
    </div>


    <div class="order-search p-2 rounded mb-3 d-flex align-items-center py-0">
        <span class="iconify me-2" data-icon="majesticons:search-line"></span>
        <input type="text" class="form-control border-0 bg-transparent" placeholder="Cari semua pesanan anda disini">
    </div>


    <div class="case">
        {{-- <div class="card order-card mb-3 position-relative py-0">
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
        </div> --}}
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
                <div class="text-center py-5">
                    <h6 class="text-muted">
                        Tidak ada pesanan yang sedang dikirim
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
                                    ${itemsHtml}
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
                                    Hubungi Penjual
                                </button>

                                <button
                                    class="btn btn-main btn-sm btn-complete"
                                    data-id="${order.id}">
                                    Selesai
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
