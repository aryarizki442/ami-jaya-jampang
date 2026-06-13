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
                    <h6 class="fw-semibold mb-0">Detail Notifikasi</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalBody">
                    <!-- Isi modal akan diisi oleh JS -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-close-modal" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

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
                    iconClass = 'notif-danger';
                    icon = 'mdi:close-circle';
                    title = 'Pesanan anda dibatalkan';
                    break;
                case 'refunded':
                    iconClass = 'notif-warning';
                    icon = 'mdi:currency-usd-off';
                    title = 'Pesanan telah direfund';
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
                    statusText = 'Dibatalkan';
                    break;
                default:
                    statusText = order.status;
            }

            const modalBody = `
                <div class="modal-status-icon ${iconClass}" style="width: 60px; height: 60px; margin: 0 auto 16px;">
                    <iconify-icon icon="${icon}" style="font-size: 30px;"></iconify-icon>
                </div>
                <div class="modal-status-title">${title}</div>
                <div class="modal-status-time">${formatShortDate(order.created_at)}</div>

                <div class="modal-divider"></div>

                <div class="modal-info-row">
                    <span class="modal-info-label">No. Pesanan</span>
                    <span class="modal-info-value">${order.order_number || '#' + order.id}</span>
                </div>
                <div class="modal-info-row">
                    <span class="modal-info-label">Status</span>
                    <span class="modal-info-value">${statusText}</span>
                </div>
                <div class="modal-info-row">
                    <span class="modal-info-label">Total Pembayaran</span>
                    <span class="modal-info-value">${formatRupiah(order.total_amount || 0)}</span>
                </div>
                ${order.shipping_address ? `
                                                                                                        <div class="modal-info-row">
                                                                                                            <span class="modal-info-label">Alamat Pengiriman</span>
                                                                                                            <span class="modal-info-value">${order.shipping_address}</span>
                                                                                                        </div>
                                                                                                        ` : ''}
            `;

            document.getElementById('modalBody').innerHTML = modalBody;

            if (!notifModal) {
                notifModal = new bootstrap.Modal(document.getElementById('notifModal'));
            }
            notifModal.show();
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
    </script>
@endsection
