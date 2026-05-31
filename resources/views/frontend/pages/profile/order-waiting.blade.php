@extends('frontend.pages.profile.account')

@section('title', 'Menunggu Pembayaran')

@section('account-content')



    <div class="order-title mb-3 mt-5">
        Menunggu Pembayaran
    </div>


    <div class="order-search p-2 rounded mb-3 d-flex align-items-center py-0">
        <span class="iconify me-2" data-icon="majesticons:search-line"></span>
        <input type="text" class="form-control border-0 bg-transparent" placeholder="Cari semua pesanan anda disini">
    </div>


    <div class="case" id="orderList"></div>


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

        function getPaymentImage(code) {
            const images = {
                cod: 'cod.png',
                bca_va: 'bca.png',
                bni_va: 'bni.png',
                bri_va: 'bri.png',
                mandiri_va: 'mandiri.png',
                qris: 'qris.jpg'
            };

            return `/images/payments/bank/${images[code] || 'bca.png'}`;
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
