@extends('frontend.pages.profile.account')

@section('title', 'Notifikasi')

@section('account-content')

    <style>
        .notif-title {
            background: #2a7b4f;
            color: white;
            text-align: center;
            padding: 12px;
            font-weight: 600;
            font-size: 20px;
            border-radius: 2px;
        }

        .notif-search {
            background: #fff;
            border: 1px solid #eee;
            display: flex;
            align-items: center;
            padding: 12px 16px;
            margin-top: 15px;
            border-radius: 8px;
        }

        .notif-search input {
            border: none;
            outline: none;
            width: 100%;
            margin-left: 10px;
        }

        .notif-tabs {
            display: flex;
            gap: 30px;
            margin-top: 20px;
            border-bottom: 1px solid #eee;
        }

        .notif-tab {
            padding-bottom: 10px;
            cursor: pointer;
            color: #999;
            transition: .2s;
        }

        .notif-tab.active {
            color: #2a7b4f;
            border-bottom: 2px solid #2a7b4f;
            font-weight: 600;
        }

        .notif-group {
            margin-top: 20px;
        }

        .notif-date {
            color: #999;
            font-weight: 500;
            margin-bottom: 10px;
            padding-left: 10px;
        }

        .notif-item {
            background: #fff;
            border-bottom: 1px solid #f1f1f1;
            padding: 18px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            cursor: pointer;
            transition: .2s;
        }

        .notif-item:hover {
            background: #f8f9fa;
        }

        .notif-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .notif-icon {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .notif-success {
            background: #e9f8ee;
            color: #22c55e;
        }

        .notif-warning {
            background: #fff5e6;
            color: #f59e0b;
        }

        .notif-danger {
            background: #ffeaea;
            color: #ef4444;
        }

        .notif-info {
            background: #e6f1fb;
            color: #3b82f6;
        }

        .notif-title-text {
            font-weight: 500;
            margin-bottom: 3px;
            font-size: 14px;
        }

        .notif-time {
            color: #999;
            font-size: 12px;
        }

        /* MODAL */
        .modal-notif .modal-content {
            border-radius: 16px;
            border: none;
        }

        .modal-notif .modal-header {
            border-bottom: 1px solid #eee;
            padding: 20px;
        }

        .modal-notif .modal-body {
            padding: 24px;
        }

        .modal-notif .modal-footer {
            border-top: 1px solid #eee;
            padding: 16px 20px;
        }

        .modal-status-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
        }

        .modal-status-title {
            font-size: 18px;
            font-weight: 600;
            text-align: center;
            margin-bottom: 8px;
        }

        .modal-status-time {
            text-align: center;
            color: #999;
            font-size: 13px;
            margin-bottom: 20px;
        }

        .modal-divider {
            height: 1px;
            background: #eee;
            margin: 16px 0;
        }

        .modal-info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
        }

        .modal-info-label {
            color: #666;
            font-size: 13px;
        }

        .modal-info-value {
            font-weight: 500;
            font-size: 13px;
            color: #1a1a1a;
        }

        .btn-close-modal {
            background: #f5f5f5;
            border: none;
            padding: 8px 20px;
            border-radius: 8px;
            font-size: 14px;
        }

        .btn-track {
            background: linear-gradient(90deg, #0D3523, #269B66);
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 8px;
            font-size: 14px;
        }

        .notif-secondary {
            background: #f1f3f5;
            color: #6c757d;
        }

        .notif-item {
            position: relative;
        }

        .notif-badge-unread {
            position: absolute;
            top: 0;
            right: 0;

            background-color: var(--primary-500);
            color: var(--white);

            padding: 10px 18px;

            border-bottom-left-radius: 16px;
            border-top-left-radius: 0;
            border-bottom-right-radius: 0;
            border-top-right-radius: 0;

            font-size: 12px;
            z-index: 10;
        }

        .notif-description-header {
            font-weight: 600;
            font-size: 14px;
            color: #1F7D53;
            margin-bottom: 14px;
        }


        .notif-description-box {
            margin-top: 20px;
            padding: 16px;
            background: #f8faf9;
            border-radius: 12px;
            border: 1px solid #e8eee9;
        }


        .notif-description-content {
            color: #555;
            font-size: 13px;
            line-height: 1.7;
        }


        .notif-description-content p {
            margin-bottom: 10px;
        }


        .notif-description-content p:last-child {
            margin-bottom: 0;
        }
    </style>

    <div class="notif-title mt-5">
        Notifikasi
    </div>

    <div class="notif-search">
        <iconify-icon icon="majesticons:search-line"></iconify-icon>
        <input type="text" id="searchInput" placeholder="Cari semua pesanan anda disini">
    </div>

    <div class="notif-tabs">
        <div class="notif-tab active" data-filter="all">Semua</div>
        <div class="notif-tab" data-filter="unread">Belum dibaca</div>
    </div>

    <div id="notificationContainer" class="mb-5"></div>

    <!-- MODAL DETAIL NOTIFIKASI -->
    <div class="modal fade modal-notif" id="notifModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="fw-semibold mb-0 mt-2 ">Status Pesanan</h4>
                    <button type="button" class="btn-close mb-0" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-divider "></div>
                <div class="modal-body" id="modalBody">
                    <!-- Isi modal akan diisi oleh JS -->
                </div>
                <div class="modal-footer" id="modalFooter">
                    <button type="button" class="btn-close-modal" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Reorder Success Modal -->
    <div class="modal fade" id="reorderModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4">

                <div class="modal-body text-center p-4">

                    <div class="mb-3">
                        <i class="bi bi-check-circle-fill text-success" style="font-size:60px;"></i>
                    </div>

                    <h5 class="fw-bold" id="reorderModalTitle">
                        Berhasil
                    </h5>

                    <p class="text-muted mb-4" id="reorderModalMessage">
                        Produk berhasil dimasukkan ke keranjang
                    </p>

                    <button class="btn btn-main px-5 rounded-3" data-bs-dismiss="modal">
                        OK
                    </button>

                </div>

            </div>
        </div>
    </div>
    @include('frontend.components.transaction-detail-modal')
    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            fetchNotifications();

            const searchInput = document.getElementById('searchInput');
            if (searchInput) {
                searchInput.addEventListener('input', filterNotifications);
            }

            document.querySelectorAll('.notif-tab').forEach(tab => {
                tab.addEventListener('click', function() {
                    document.querySelectorAll('.notif-tab').forEach(t => t.classList.remove(
                        'active'));
                    this.classList.add('active');
                    filterNotifications();
                });
            });
        });

        let allNotifications = [];
        let currentOrderId = null;
        let notifModal = null;

        function formatNotifDate(dateString) {
            const date = new Date(dateString);
            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const dayName = days[date.getDay()];

            return `${dayName}, ${date.getDate()} ${date.toLocaleString('id-ID', { month: 'long' })} ${date.getFullYear()} ${date.getHours().toString().padStart(2,'0')}:${date.getMinutes().toString().padStart(2,'0')} WIB`;
        }

        function formatShortDate(dateString) {
            const date = new Date(dateString);
            return `${date.getDate()} ${date.toLocaleString('id-ID', { month: 'long' })} ${date.getFullYear()} ${date.getHours().toString().padStart(2,'0')}:${date.getMinutes().toString().padStart(2,'0')} WIB`;
        }

        function getNotifIconAndClass(status, paymentStatus = null) {
            let iconClass = 'notif-success';
            let icon = 'mdi:check-decagram';
            let title = '';

            switch (status) {
                case 'awaiting_payment':
                case 'pending':
                    iconClass = 'notif-secondary';
                    icon = 'mdi:clock-outline';
                    title = 'Menunggu pembayaran anda';
                    break;

                case 'paid':
                    iconClass = 'notif-warning';
                    icon = 'mdi:cog-outline';
                    title = 'Pesanan sedang diproses';
                    break;
                case 'processing':
                    iconClass = 'notif-info';
                    icon = 'mdi:cog-outline';
                    title = 'Pesanan sedang diproses';
                    break;
                case 'shipped':
                    iconClass = 'notif-info';
                    icon = 'mdi:truck-delivery';
                    title = 'Pesanan sedang dikirim';
                    break;
                case 'ready_for_pickup':
                    iconClass = 'notif-info';
                    icon = 'mdi:store';
                    title = 'Pesanan siap dijemput';
                    break;
                case 'completed':
                    iconClass = 'notif-success';
                    icon = 'mdi:check-decagram';
                    title = 'Pesanan anda selesai';
                    break;
                case 'cancelled':
                case 'refunded':
                    iconClass = 'notif-danger';
                    icon = 'mdi:close-circle';
                    title = 'Pesanan anda dibatalkan';
                    break;
                case 'expired':
                    iconClass = 'notif-danger';
                    icon = 'mdi:timer-off-outline';
                    title = 'Pembayaran kedaluwarsa';
                    break;
                default:
                    title = status;
            }

            return {
                iconClass,
                icon,
                title
            };
        }

        // Ambil status baca dari localStorage
        function isNotificationRead(orderId) {

            orderId = Number(orderId);

            const readIds = JSON.parse(
                localStorage.getItem('read_notifications') || '[]'
            );

            return readIds.includes(orderId);
        }

        function markAsRead(orderId) {

            orderId = Number(orderId);

            const readIds = JSON.parse(
                localStorage.getItem('read_notifications') || '[]'
            );

            if (!readIds.includes(orderId)) {

                readIds.push(orderId);

                localStorage.setItem(
                    'read_notifications',
                    JSON.stringify(readIds)
                );
            }
        }

        async function fetchNotifications() {
            try {
                const token = localStorage.getItem('token');
                const response = await fetch('http://127.0.0.1:8000/api/orders', {
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${token}`
                    }
                });

                const result = await response.json();
                const orders = result?.data?.data || [];

                allNotifications = orders.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
                renderNotifications(allNotifications);

            } catch (error) {
                console.error(error);
                document.getElementById('notificationContainer').innerHTML = `
                    <div class="text-center py-5 text-danger">
                        Gagal memuat notifikasi
                    </div>
                `;
            }
        }

        function renderNotifications(notifications) {
            const container = document.getElementById('notificationContainer');

            if (notifications.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-5">
                        <iconify-icon icon="mdi:bell-off-outline" style="font-size: 64px; color: #ccc;"></iconify-icon>
                        <p class="mt-3 text-muted">Notifikasi sudah dibaca</p>
                    </div>
                `;
                return;
            }

            // Group by date
            const grouped = {};
            notifications.forEach(notif => {
                const date = new Date(notif.created_at).toLocaleDateString('id-ID', {
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric'
                });
                if (!grouped[date]) grouped[date] = [];
                grouped[date].push(notif);
            });

            let html = '';
            for (const [date, items] of Object.entries(grouped)) {
                html += `<div class="notif-group">`;
                html += `<div class="notif-date">${date}</div>`;

                items.forEach(notif => {
                    const {
                        iconClass,
                        icon,
                        title
                    } = getNotifIconAndClass(notif.status);
                    const isRead = isNotificationRead(notif.id);

                    html += `
                        <div class="notif-item" data-status="${notif.status}" data-id="${notif.id}" data-order='${JSON.stringify(notif)}'>
                            <div class="notif-left">
                                <div class="notif-icon ${iconClass}">
                                    <iconify-icon icon="${icon}" style="font-size: 20px;"></iconify-icon>
                                </div>
                                <div>
                                    <div class="notif-title-text">${title}</div>
                                    <div class="notif-time">${formatNotifDate(notif.created_at)}</div>
                                </div>
                            </div>
                            ${!isRead ? '<div class="notif-badge-unread">Belum dibaca</div>' : ''}
                        </div>
                    `;
                });

                html += `</div>`;
            }

            container.innerHTML = html;

            // Event listener untuk setiap notif item
            document.querySelectorAll('.notif-item').forEach(item => {
                item.addEventListener('click', function(e) {
                    // Jangan trigger jika klik pada badge
                    if (e.target.classList.contains('notif-badge-unread')) return;

                    const orderData = JSON.parse(this.dataset.order);
                    const orderId = Number(this.dataset.id);

                    // Mark as read
                    if (!isNotificationRead(orderId)) {
                        markAsRead(orderId);
                        // Update tampilan badge
                        const badge = this.querySelector('.notif-badge-unread');
                        if (badge) badge.remove();
                    }

                    // Tampilkan modal
                    showModalDetail(orderData);
                });
            });
        }

        // Tampilkan modal detail
        function showModalDetail(order) {
            const {
                iconClass,
                icon,
                title
            } = getNotifIconAndClass(order.status);
            currentOrderId = order.id;

            let statusText = '';
            switch (order.status) {
                case 'awaiting_payment':
                    statusText = 'Menunggu Pembayaran';
                    break;
                case 'paid':
                    statusText = 'Sudah Dibayar';
                    break;
                case 'processing':
                    statusText = 'Diproses';
                    break;
                case 'shipped':
                    statusText = 'Dikirim';
                    break;
                case 'completed':
                    statusText = 'Selesai';
                    break;
                case 'cancelled':
                case 'refunded':
                    statusText = 'Dibatalkan';
                    break;

                default:
                    statusText = order.status;
            }

            const description = getNotifDescription(order.status);

            const modalBody = `

                <div class="modal-status-icon ${iconClass}"
                    style="width:60px;height:60px;margin:0 auto 16px;">
                    <iconify-icon
                        icon="${icon}"
                        style="font-size:30px;">
                    </iconify-icon>
                </div>

                <div class="modal-status-title">
                    ${title}
                </div>

                <div class="modal-status-time">
                    ${formatShortDate(order.created_at)}
                </div>


                 ${order.shipping_address ? `
                                                                                                                                                                                                                                                                                                  <div class="modal-info-row">
                                                                                                                                                                                                                                                                                                 <span class="modal-info-label">
                                                                                                                                                                                                                                                                                                  Alamat Pengiriman
                                                                                                                                                                                                                                                                                                  </span>
                                                                                                                                                                                                                                                                                                <span class="modal-info-value">
                                                                                                                                                                                                                                                                                                     ${order.shipping_address}
                                                                                                                                                                                                                                                                                                     </span>
                                                                                                                                                                                                                                                                                                  </div>
                                                                                                                                                                                                                                                                                                  ` : ''}


                                ${description ? `
                                                                                                                                                                                                                                                                                                  <div class="modal-divider"></div>
                                                                                                                                                                                                                                                                                                <div>
                                                                                                                                                                                                                                                                                                ${description}
                                                                                                                                                                                                                                                                                                </div>
                                                                                                                                                                                                                                                                                                   ` : ''}
                    `;

            document.getElementById('modalBody').innerHTML = modalBody;

            if (!notifModal) {
                notifModal = new bootstrap.Modal(document.getElementById('notifModal'));
            }
            notifModal.show();

            const modalFooter = document.getElementById('modalFooter');

            switch (order.status) {

                // Menunggu pembayaran
                case 'awaiting_payment':
                case 'pending':
                    modalFooter.innerHTML = `
        <button
            class="btn btn-main px-4 py-2 fw-semibold rounded-3"
            id="trxPayBtn">
            Bayar Sekarang
        </button>
    `;

                    document.getElementById('trxPayBtn').onclick = function() {
                        processPayment(order.id);
                    };
                    break;

                    // Sedang diproses / dikirim / siap dijemput
                case 'paid':
                case 'processing':
                case 'shipped':
                case 'ready_for_pickup':
                    modalFooter.innerHTML = `
            <button class="btn btn-main px-4 py-2 fw-semibold rounded-3"
                id="contactSellerBtn">
                Hubungi Penjual
            </button>
        `;
                    break;

                    // Selesai / dibatalkan
                case 'completed':
                case 'cancelled':
                case 'refunded':
                    modalFooter.innerHTML = `
            <button class="btn btn-main px-4 py-2 fw-semibold rounded-3"
                id="detailOrderBtn">
                Detail Pesanan
            </button>
        `;
                    break;

                    // Default
                default:
                    modalFooter.innerHTML = `
            <button type="button"
                class="btn-close-modal"
                data-bs-dismiss="modal">
                Tutup
            </button>
        `;
            }

            document.getElementById('trxPayBtn')?.addEventListener('click', function() {
                processPayment(order.id);
            });

            // Hubungi Penjual
            document.getElementById('contactSellerBtn')?.addEventListener('click', function() {
                window.open(
                    'https://wa.me/6281211223344?text=Halo%20saya%20membutuhkan%20bantuan!',
                    '_blank'
                );
            });

            // Detail Pesanan
            document.getElementById('detailOrderBtn')?.addEventListener('click', function() {

                openTransactionDetail(order.id);

            });
        }

        function getNotifDescription(status) {

            let content = '';

            switch (status) {

                case 'cancelled':
                    content = `
                <p>
                    Pesanan Anda telah dibatalkan
                    <br>
                    <br>
                    Stok produk tidak tersedia, kendala operasional,
                    atau informasi pesanan yang tidak dapat diproses.
                    <br>
                    <br>
                    Jika pembayaran telah dilakukan, dana akan dikembalikan
                    dengan berkomunikasi dengan penjual terlebih dahulu.
                    <br>
                <p>Terimakasih.</p>
            `;
                    break;


                case 'refunded':
                    content = `
                <p>
                    Pesanan Anda telah dibatalkan
                    <br>
                    Stok produk tidak tersedia, kendala operasional,
                    atau informasi pesanan yang tidak dapat diproses.
                    <br>
                    Jika pembayaran telah dilakukan, dana akan dikembalikan
                    dengan berkomunikasi dengan penjual terlebih dahulu.
                    <br>
                <p>Terimakasih.</p>
            `;
                    break;


                case 'completed':
                    content = `
                <p><strong>Pesanan Anda telah selesai!</strong>
                    <br>
                        Terima kasih telah berbelanja bersama kami.
                        Kami berharap produk yang Anda terima sesuai dengan harapan Anda.
                    <br>
                    <br>
                        Jangan lupa berikan penilaian untuk membantu kami
                        meningkatkan kualitas layanan.
                    <br>
                    <br>
               <p> Selamat menikmati produk Anda!</p>
            `;
                    break;


                case 'shipped':
                    content = `
                <p>
                  Pesanan Anda telah kami terima dan saat ini menunggu pembayaran.
                    <br>
                    <br>
                    Silakan lakukan pembayaran sebelum batas waktu yang ditentukan
                    agar pesanan dapat segera diproses.
                    <br>
                <p>
                Terimakasih.</p>
            `;
                    break;


                case 'ready_for_pickup':
                    content = `
                <p>
                   Pesanan Anda telah selesai dipersiapkan dan siap untuk diambil di lokasi toko.
                    <br>
                    <br>
                    Silakan datang sesuai jam operasional dengan menunjukkan nomor pesanan.
                    <br>

                </p>
            `;
                    break;


                case 'awaiting_payment':
                case 'pending':
                    content = `
                <p>
                   Pesanan Anda telah kami terima dan saat ini menunggu pembayaran.
                    <br>
                    <br>
                    Silakan lakukan pembayaran sebelum batas waktu yang ditentukan
                    agar pesanan dapat segera diproses.
                </p>
            `;
                    break;


                case 'paid':
                case 'processing':
                    content = `
                <p>
                    Pesanan Anda sedang dipersiapkan oleh penjual.
                    <br>
                    <br>
                    Kami akan segera mengirimkan informasi lebih lanjut setelah
                    pesanan siap dikirim atau diambil.
                </p>
            `;
                    break;


                default:
                    return '';
            }


            return `
            <div class="notif-description-content">
                ${content}
            </div>
    `;
        }


        function formatRupiah(angka) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(angka);
        }

        // Tombol lacak pesanan
        document.getElementById('trackOrderBtn')?.addEventListener('click', function() {
            if (currentOrderId) {
                if (notifModal) notifModal.hide();
                window.location.href = `/profile/orders/${currentOrderId}`;
            }
        });

        // Fungsi untuk memuat script Snap Midtrans
        function loadSnapScript(clientKey) {
            return new Promise((resolve, reject) => {
                // Cek apakah sudah ada
                if (typeof window.snap !== 'undefined') {
                    resolve();
                    return;
                }

                const script = document.createElement('script');
                script.src = 'https://app.sandbox.midtrans.com/snap/snap.js';
                script.setAttribute('data-client-key', clientKey);
                script.onload = () => {
                    console.log('Snap script loaded');
                    resolve();
                };
                script.onerror = () => {
                    reject(new Error('Gagal memuat Midtrans Snap'));
                };
                document.head.appendChild(script);
            });
        }
        async function processPayment(orderId) {
            const token = localStorage.getItem('token');
            const payBtn = document.getElementById('trxPayBtn');

            if (!token) {
                showAlert('Silakan login terlebih dahulu', 'error');
                return;
            }

            // Simpan orderId untuk referensi
            currentOrderForPayment = orderId;

            // Tampilkan loading
            if (payBtn) {
                payBtn.disabled = true;
                payBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Memproses...';
            }

            try {
                // Ambil snap token dari backend
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
                    throw new Error(snapJson.message || 'Gagal membuat token pembayaran');
                }

                const {
                    snap_token,
                    client_key,
                    snap_url
                } = snapJson.data;

                // Pastikan snap sudah dimuat
                if (typeof window.snap === 'undefined') {
                    // Load Snap script jika belum ada
                    await loadSnapScript(client_key);
                }

                // Buka popup pembayaran Midtrans
                window.snap.pay(snap_token, {
                    onSuccess: function(result) {
                        console.log('Payment Success:', result);
                        showAlert('Pembayaran berhasil!', 'success');
                        // Tutup modal
                        const modal = bootstrap.Modal.getInstance(document.getElementById(
                            'notifModal'));
                        if (modal) modal.hide();
                        // Refresh halaman atau load ulang data pesanan
                        setTimeout(() => {
                            if (typeof loadWaitingOrders === 'function') {
                                loadWaitingOrders();
                            } else {
                                location.reload();
                            }
                        }, 1500);
                    },
                    onPending: function(result) {
                        console.log('Payment Pending:', result);
                        showAlert('Menunggu konfirmasi pembayaran', 'info');
                        // Tutup modal
                        const modal = bootstrap.Modal.getInstance(document.getElementById(
                            'notifModal'));
                        if (modal) modal.hide();
                        setTimeout(() => {
                            if (typeof loadWaitingOrders === 'function') {
                                loadWaitingOrders();
                            } else {
                                location.reload();
                            }
                        }, 1500);
                    },
                    onError: function(result) {
                        console.error('Payment Error:', result);
                        showAlert('Pembayaran gagal. Silakan coba lagi.', 'error');
                        if (payBtn) {
                            payBtn.disabled = false;
                            payBtn.innerHTML = '</i> Bayar Sekarang';
                        }
                    },
                    onClose: function() {
                        console.log('Payment popup closed');
                        // User menutup popup tanpa menyelesaikan pembayaran
                        if (payBtn) {
                            payBtn.disabled = false;
                            payBtn.innerHTML = '</i> Bayar Sekarang';
                        }
                        showAlert('Pembayaran dibatalkan', 'info');
                    }
                });

            } catch (err) {
                console.error('Payment error:', err);
                showAlert(err.message || 'Terjadi kesalahan saat memproses pembayaran', 'error');
                if (payBtn) {
                    payBtn.disabled = false;
                    payBtn.innerHTML = '<i class="bi bi-credit-card"></i> Bayar Sekarang';
                }
            }
        }

        async function openTransactionDetail(orderId) {

            const token = localStorage.getItem('token');

            if (!token) {
                showAlert('Silakan login terlebih dahulu', 'error');
                return;
            }

            const modalElement = document.getElementById(
                'transactionDetailModal'
            );
            console.log(modalElement);
            const productContainer = document.getElementById(
                'trxProductItems'
            );

            try {

                // Loading saat data sedang diambil
                if (productContainer) {
                    productContainer.innerHTML = `
                <div class="text-center py-3">
                    Memuat data pesanan...
                </div>
            `;
                }

                const response = await fetch(
                    `/api/orders/${orderId}`, {
                        headers: {
                            'Accept': 'application/json',
                            'Authorization': `Bearer ${token}`
                        }
                    }
                );

                const result = await response.json();

                if (!response.ok) {
                    throw new Error(
                        result.message ||
                        'Gagal mengambil detail transaksi'
                    );
                }

                // Sesuaikan dengan response API
                const order = result.data;

                if (!order) {
                    throw new Error(
                        'Data pesanan tidak ditemukan'
                    );
                }

                console.log(
                    'Detail transaksi dari notifikasi:',
                    order
                );

                /*
                ==================================
                STATUS
                ==================================
                */

                document.getElementById(
                    'trxStatus'
                ).innerHTML = getStatusHtml(
                    order.status,
                    order.delivery_method
                );

                /*
                ==================================
                NOMOR DAN TANGGAL PESANAN
                ==================================
                */

                document.getElementById(
                        'trxOrderId'
                    ).textContent =
                    order.order_number ||
                    '#' + order.id;

                document.getElementById(
                        'trxDate'
                    ).textContent =
                    formatDateIndonesia(
                        order.created_at
                    );

                /*
                ==================================
                DETAIL PRODUK
                ==================================
                */

                const items = order.items || [];

                if (items.length === 0) {

                    productContainer.innerHTML = `
                <div class="text-muted">
                    Tidak ada produk
                </div>
            `;

                } else {

                    let productsHtml = '';

                    items.forEach(
                        (item, index) => {

                            const productName =
                                item.name ||
                                item.product_name ||
                                '-';

                            const quantity =
                                item.quantity || 1;

                            const price =
                                item.unit_price ||
                                item.price ||
                                0;

                            const image =
                                item.image ||
                                item.product_image ||
                                '/images/placeholder.png';

                            const productId =
                                item.product_id;

                            productsHtml += `

                        <div class="
                            d-flex
                            justify-content-between
                            align-items-start
                            mb-3
                            pb-2
                            ${
                                index !==
                                items.length - 1
                                    ? 'border-bottom'
                                    : ''
                            }
                        ">

                            <div class="d-flex gap-3">

                                <img
                                    src="${image}"

                                    style="
                                        width:70px;
                                        height:70px;
                                        object-fit:cover;
                                        border-radius:8px;
                                    "

                                    onerror="
                                        this.src=
                                        '/images/placeholder.png'
                                    "
                                >

                                <div>

                                    <div class="fw-semibold">

                                        ${escapeHtml(
                                            productName
                                        )}

                                    </div>

                                    <small class="text-muted">

                                        ${quantity}x
                                        ${formatRupiah(
                                            price
                                        )}

                                    </small>

                                    ${
                                        item.unit
                                        ? `
                                                                    <div
                                                                        class="
                                                                            text-muted
                                                                            small
                                                                        "
                                                                    >
                                                                        Satuan:
                                                                        ${escapeHtml(
                                                                            item.unit
                                                                        )}
                                                                    </div>
                                                                `
                                        : ''
                                    }

                                </div>

                            </div>

                            ${
                                productId
                                ? `
                                                            <a
                                                                href="/product/${productId}"

                                                                class="
                                                                    text-success
                                                                    fw-semibold
                                                                    small
                                                                    text-decoration-none
                                                                "
                                                            >
                                                                Lihat Produk
                                                            </a>
                                                        `
                                : ''
                            }

                        </div>

                    `;

                        }
                    );

                    productContainer.innerHTML =
                        productsHtml;

                }

                /*
                ==================================
                RINCIAN PEMBAYARAN
                ==================================
                */

                const summary =
                    order.summary || {};

                const payment =
                    order.payment || {};

                document.getElementById(
                        'trxPaymentMethod'
                    ).textContent =
                    payment.method ||
                    order.payment_method ||
                    '-';

                document.getElementById(
                        'trxSubtotal'
                    ).textContent =
                    summary.subtotal_format ||
                    formatRupiah(
                        summary.subtotal || 0
                    );

                document.getElementById(
                        'trxShipping'
                    ).textContent =
                    summary.shipping_cost_format ||
                    formatRupiah(
                        summary.shipping_cost || 0
                    );

                document.getElementById(
                        'trxFee'
                    ).textContent =
                    summary.other_fee_format ||
                    formatRupiah(
                        summary.other_fee || 0
                    );

                document.getElementById(
                        'trxTotal'
                    ).textContent =
                    summary.total_format ||
                    formatRupiah(
                        summary.total || 0
                    );

                /*
                ==================================
                RESET SEMUA TOMBOL
                ==================================
                */

                const cancelBtn =
                    document.getElementById(
                        'trxCancelBtn'
                    );

                const payBtn =
                    document.getElementById(
                        'trxPayBtn'
                    );

                const chatBtn =
                    document.getElementById(
                        'trxChatBtn'
                    );

                const reorderBtn =
                    document.getElementById(
                        'trxReorderBtn'
                    );

                const contactSellerBtn =
                    document.getElementById(
                        'trxContactSellerBtn'
                    );

                [
                    cancelBtn,
                    payBtn,
                    chatBtn,
                    reorderBtn,
                    contactSellerBtn
                ].forEach(btn => {

                    if (btn) {

                        btn.style.display =
                            'none';

                        btn.onclick =
                            null;

                    }

                });

                /*
                ==================================
                TOMBOL BERDASARKAN STATUS
                ==================================
                */

                if (
                    order.status ===
                    'awaiting_payment' ||

                    order.status ===
                    'pending'
                ) {

                    if (cancelBtn) {

                        cancelBtn.style.display =
                            'flex';

                        cancelBtn.onclick =
                            () =>
                            showCancelModal(
                                order.id
                            );

                    }

                    if (payBtn) {

                        payBtn.style.display =
                            'flex';

                        payBtn.onclick =
                            () =>
                            processPayment(
                                order.id
                            );

                    }

                } else if (
                    order.status ===
                    'completed'
                ) {

                    if (chatBtn) {

                        chatBtn.style.display =
                            'flex';

                        chatBtn.onclick =
                            () =>
                            chatSeller(order);

                    }

                    if (reorderBtn) {

                        reorderBtn.style.display =
                            'flex';

                        reorderBtn.onclick =
                            () =>
                            reorderOrder(
                                order.id
                            );

                    }

                } else if (
                    order.status ===
                    'cancelled' ||

                    order.status ===
                    'refunded'
                ) {

                    if (contactSellerBtn) {

                        contactSellerBtn.style.display =
                            'flex';

                        contactSellerBtn.onclick =
                            () =>
                            contactSeller(
                                order
                            );

                    }

                }

                /*
                ==================================
                TAMPILKAN MODAL
                ==================================
                */

                const transactionModal =
                    bootstrap.Modal
                    .getOrCreateInstance(
                        modalElement
                    );

                transactionModal.show();

            } catch (error) {

                console.error(
                    'Gagal membuka detail transaksi:',
                    error
                );

                if (productContainer) {

                    productContainer.innerHTML = `

                <div class="
                    text-center
                    text-danger
                    py-3
                ">

                    Gagal memuat
                    data pesanan

                </div>

            `;

                }

                showAlert(
                    error.message ||
                    'Gagal memuat detail pesanan',
                    'error'
                );

            }

        }

        function filterNotifications() {
            const searchQuery = document.getElementById('searchInput')?.value.toLowerCase() || '';
            const activeFilter = document.querySelector('.notif-tab.active')?.dataset.filter || 'all';

            let filtered = [...allNotifications];

            if (searchQuery) {
                filtered = filtered.filter(notif => {
                    const {
                        title
                    } = getNotifIconAndClass(notif.status);
                    return title.toLowerCase().includes(searchQuery) ||
                        (notif.order_number || '').toLowerCase().includes(searchQuery);
                });
            }

            if (activeFilter === 'unread') {
                filtered = filtered.filter(notif => !isNotificationRead(notif.id));
            }

            renderNotifications(filtered);
        }

        function showAlert(message, type = 'info') {
            alert(message);
        }
        async function reorderOrder(orderId, btn) {

            const token = localStorage.getItem('token');

            if (!token) {
                window.location.href = "{{ route('login') }}";
                return;
            }


            if (btn) {
                btn.disabled = true;
                btn.innerHTML = `
                <span class="spinner-border spinner-border-sm"></span>
                Memproses...
            `;
            }


            try {

                const response = await fetch(
                    `/api/orders/${orderId}/reorder`, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Authorization': `Bearer ${token}`,
                            'Content-Type': 'application/json'
                        }
                    }
                );


                const result = await response.json();


                if (!response.ok || !result.success) {
                    throw new Error(
                        result.message || 'Gagal membeli ulang'
                    );
                }


                // Tutup modal detail transaksi
                const trxModal = bootstrap.Modal.getInstance(
                    document.getElementById('transactionDetailModal')
                );

                if (trxModal) {
                    trxModal.hide();
                }

                // Tunggu animasi modal selesai
                setTimeout(() => {

                    showReorderModal(
                        result.message || 'Produk berhasil dimasukkan ke keranjang',
                        'success'
                    );

                    // Redirect setelah user melihat modal sukses
                    setTimeout(() => {
                        window.location.href = "{{ route('cart') }}";
                    }, 1500);

                }, 300);


            } catch (error) {


                showReorderModal(
                    error.message || 'Terjadi kesalahan',
                    'error'
                );

                if (btn) {
                    btn.disabled = false;
                    btn.innerHTML = 'Beli Lagi';
                }

            }

        }

        function showReorderModal(message, type = 'success') {

            const modal = document.getElementById('reorderModal');

            const icon = modal.querySelector('i');
            const title = document.getElementById('reorderModalTitle');
            const text = document.getElementById('reorderModalMessage');


            if (type === 'success') {

                icon.className =
                    'bi bi-check-circle-fill text-success';

                title.innerText = 'Berhasil';

            } else {

                icon.className =
                    'bi bi-x-circle-fill text-danger';

                title.innerText = 'Gagal';

            }


            text.innerText = message;


            const bsModal = bootstrap.Modal.getOrCreateInstance(modal);

            bsModal.show();

        }
    </script>
@endsection
