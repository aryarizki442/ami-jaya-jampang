@extends('backend.app')

@section('title', 'Pesanan')

@section('content')

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        .main {
            min-width: 0;
        }

        /* CONTENT */
        .content {
            overflow-x: auto;
        }

        /* TABLE RESPONSIVE */
        .table-responsive {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        /* TABLE */
        .custom-table {
            width: 100%;
            table-layout: auto;
        }

        .name-text {
            max-width: 350px;
            white-space: normal;
            overflow-wrap: break-word;
        }

        .custom-table thead th {
            font-size: 14px;
            font-weight: 500;
        }

        .custom-table tbody td {
            font-size: 14px;
            vertical-align: middle;
        }

        .custom-table.table-hover tbody tr:hover td {
            background: var(--primary-50) !important;
            cursor: pointer;
        }

        /* HEADER STYLE */
        thead tr {
            background: linear-gradient(90deg, #0D3523, #269B66);
        }

        thead th {
            background: transparent !important;
            color: white !important;
            border: none;
        }

        /* ACTION ICON */
        .action-icon {
            font-size: 20px;
            display: inline-flex;
            transition: 0.2s;
        }

        .action-icon:hover {
            transform: scale(1.15);
        }

        /* BADGE STYLES */
        .badge-waiting-payment {
            background-color: var(--neutral-50);
            color: var(--neutral-300);
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }

        .badge-paid {
            background-color: var(--warning-50);
            color: var(--warning-500);
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }

        .badge-shipped {
            background-color: var(--info-50);
            color: var(--info-500);
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }

        .badge-pickup {
            background-color: var(--info-50);
            color: var(--info-500);
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }

        .badge-completed {
            background-color: var(--success-50);
            color: var(--success-500);
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }

        .badge-cancelled {
            background-color: var(--danger-50);
            color: var(--danger-500);
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }

        .badge-rejected {
            background-color: var(--danger-50);
            color: var(--danger-500);
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }

        /* CHECKBOX */
        .custom-check {
            appearance: none;
            width: 15px;
            height: 15px;
            border: 1px solid var(--neutral-200);
            border-radius: 4px;
            cursor: pointer;
            position: relative;
        }

        .custom-check:checked {
            background-color: #60B5FF;
            border-color: #60B5FF;
        }

        .custom-check:checked::after {
            content: "";
            position: absolute;
            inset: 0;
            background: url("https://api.iconify.design/basil/check-solid.svg?color=white") center/14px no-repeat;
        }

        /* PAGINATION */
        .custom-pagination {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 20px;
        }

        .custom-pagination a {
            text-decoration: none;
            color: #666;
            padding: 4px 10px;
            border-radius: 6px;
            cursor: pointer;
        }

        .custom-pagination a.active {
            background-color: #eff8ff;
            color: #60b5ff;
            font-weight: 600;
        }

        /* MODAL CLEAN */
        #deleteModal .modal-content {
            border: none;
            box-shadow: none;
        }

        #deleteModal .modal-header,
        #deleteModal .modal-footer {
            border: none;
        }

        /* ===== MOBILE UI ===== */
        .select-all-row {
            display: none;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            background: #f9f9f9;
            border-radius: 8px;
            font-size: 13px;
            color: #666;
            margin-bottom: 8px;
            border: 1px solid #E8E8E9;
        }

        .order-list {
            display: none;
            flex-direction: column;
            gap: 10px;
        }

        .order-card {
            background: #fff;
            border: 1px solid #E8E8E9;
            border-radius: 12px;
            padding: 12px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
            transition: border-color 0.15s;
        }

        .order-card:hover {
            border-color: #b0b0b0;
            cursor: pointer;
        }

        .order-info {
            flex: 1;
            min-width: 0;
        }

        .order-row1 {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 6px;
            margin-bottom: 6px;
            flex-wrap: wrap;
        }

        .order-number {
            font-size: 13px;
            font-weight: 600;
            color: #1a1a1a;
        }

        .order-row2 {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 8px;
            flex-wrap: wrap;
            gap: 8px;
        }

        .order-customer {
            font-size: 12px;
            color: #666;
        }

        .order-date {
            font-size: 11px;
            color: #999;
        }

        .order-total {
            font-size: 13px;
            font-weight: 600;
            color: #059669;
        }

        .order-actions {
            display: flex;
            gap: 6px;
        }

        .act-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 30px;
            height: 30px;
            border-radius: 8px;
            border: 1px solid #E8E8E9;
            background: transparent;
            cursor: pointer;
            color: #666;
            font-size: 16px;
            text-decoration: none;
            transition: 0.15s;
        }

        .act-btn.detail:hover {
            color: #3B6D11;
            border-color: #3B6D11;
            background: #EAF3DE;
        }

        /* POSISI RELATIVE UNTUK ICON */
        .eye-action {
            position: relative;
        }

        /* TITIK MERAH NOTIFIKASI */
        .eye-action::after {
            content: '';
            position: absolute;
            right: 1px;
            width: 8px;
            height: 8px;
            background-color: #EF4444;
            border-radius: 50%;
            opacity: 0;
            transition: opacity 0.2s;
        }

        /* TAMPILKAN TITIK MERAH UNTUK STATUS PAID */
        .eye-action.has-notification::after {
            opacity: 1;
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .table-responsive {
                display: none !important;
            }

            .select-all-row {
                display: flex;
            }

            .order-list {
                display: flex;
            }
        }
    </style>

    <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
        <button id="openCalendar" class="btn btn-main btn-sm px-3 d-flex align-items-center gap-2">
            <span class="iconify" data-icon="uil:calendar" style="font-size:18px;"></span>
            <span id="currentMonth"></span>
        </button>

        @include('backend.components.calendar')
    </div>

    <div class="card border-0 shadow-sm rounded-4 p-4">

        <div class="row g-2 align-items-center mb-3">

            <!-- SEARCH -->
            <div class="col-12 col-md">
                <div class="position-relative">
                    <input type="text" id="searchInput" class="form-control pe-5" placeholder="Cari Pesanan disini">
                    <i class="ri-search-line position-absolute top-50 end-0 translate-middle-y me-3 text-muted"></i>
                </div>
            </div>

            <!-- FILTER -->
            <div class="col-6 col-md-auto position-relative">
                <button type="button" id="filterBtn"
                    class="btn btn-filter-admin w-100 d-flex align-items-center justify-content-center gap-1">
                    <span class="iconify" data-icon="mingcute:filter-line"></span>
                    Filter
                </button>

                <!-- DROPDOWN FILTER -->
                <div id="filterDropdown" class="position-absolute bg-white shadow rounded p-3 mt-2 d-none"
                    style="min-width:220px; z-index:999;">
                    <div class="fw-semibold mb-2">Filter</div>

                    <div class="small text-muted mb-1">Status Pesanan</div>
                    <select id="statusFilter" class="form-select form-select-sm mb-3">
                        <option value="">Semua Status</option>
                        <option value="awaiting_payment">Menunggu Pembayaran</option>
                        <option value="paid">Menunggu Konfirmasi</option>
                        <option value="shipped">Dikirim / Dijemput</option>
                        <option value="completed">Selesai</option>
                        <option value="cancelled">Dibatalkan</option>
                        <option value="rejected">Ditolak</option>
                    </select>

                    {{-- <div class="small text-muted mb-1">Kategori Produk</div>
                    <select id="categoryFilter" class="form-select form-select-sm">
                        <option value="">Semua Kategori</option>
                        <option value="premium">Premium</option>
                        <option value="medium">Medium</option>
                        <option value="ketan">Ketan</option>
                    </select> --}}
                </div>
            </div>

            <!-- DELETE -->
            <div class="col-6 col-md-auto">
                <button class="btn btn-delete-admin w-100" id="deleteSelected">
                    <span class="iconify" data-icon="famicons:trash-outline"></span>
                </button>
            </div>
        </div>

        {{-- SELECT ALL ROW (mobile) --}}
        <div class="select-all-row" id="selectAllRow">
            <input type="checkbox" id="checkAllMobile" class="custom-check">
            <span id="selectLabel">Pilih semua</span>
        </div>

        {{-- CARD LIST (mobile) --}}
        <div class="order-list" id="orderList"></div>

        <!-- TABLE -->
        <div class="table-responsive">
            <table class="table align-middle custom-table table-hover">
                <thead>
                    <tr class="text-center align-middle small">
                        <th style="width:40px;">
                            <input type="checkbox" id="checkAll" class="custom-check">
                        </th>
                        <th class="text-start">Pesanan</th>
                        <th class="text-start">Tanggal</th>
                        <th class="text-start">Pelanggan</th>
                        <th class="text-center">Status Pesanan</th>
                        <th class="text-start">Total</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="orderTableBody">
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            Memuat data...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- PAGINATION -->
        <div class="custom-pagination" id="paginationContainer"></div>

    </div>

    <!-- MODAL DELETE CONFIRMATION -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header justify-content-center">
                    <h5 class="fw-semibold m-0">Hapus Pesanan</h5>
                </div>
                <div class="modal-body text-center">
                    <p class="mb-0">Apakah Anda yakin ingin menghapus item yang dipilih?</p>
                </div>
                <div class="modal-footer justify-content-center gap-2">
                    <button class="btn btn-delete-second" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-delete-main" id="confirmDeleteBtn">Hapus</button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL DELETE SUCCESS -->
    <div class="modal fade" id="deleteSuccessModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content p-5 text-center">
                <div class="d-flex justify-content-center mb-3">
                    <div class="d-flex align-items-center justify-content-center rounded-circle"
                        style="width:50px; height:50px; background:#22C55E;">
                        <i class="iconify text-white fs-1" data-icon="iconamoon:check-bold"></i>
                    </div>
                </div>
                <p class="mb-0 fw-medium">Berhasil Menghapus Pesanan</p>
            </div>
        </div>
    </div>

    <script src="https://code.iconify.design/3/3.1.0/iconify.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            /* =========================
               ELEMENTS
            ========================== */
            const checkAll = document.getElementById('checkAll');
            const searchInput = document.getElementById("searchInput");
            const tbody = document.getElementById('orderTableBody');
            const deleteBtn = document.getElementById("deleteSelected");
            const confirmBtn = document.getElementById("confirmDeleteBtn");
            const statusFilter = document.getElementById('statusFilter');
            const categoryFilter = document.getElementById('categoryFilter');

            let selectedIds = [];
            let allOrders = [];

            let filters = {
                page: 1,
                search: null,
                status: null,
                category: null,
                startDate: null,
                endDate: null
            };

            /* =========================
               DROPDOWN FILTER
            ========================== */
            function initDropdown(triggerId, dropdownId) {
                const trigger = document.getElementById(triggerId);
                const dropdown = document.getElementById(dropdownId);
                if (!trigger || !dropdown) return;

                trigger.addEventListener('click', function(e) {
                    e.stopPropagation();
                    dropdown.classList.toggle('d-none');

                    dropdown.style.left = '';
                    dropdown.style.right = '';
                    const rect = dropdown.getBoundingClientRect();
                    const screenWidth = window.innerWidth;

                    if (rect.right > screenWidth) {
                        dropdown.style.left = 'auto';
                        dropdown.style.right = '0';
                    } else {
                        dropdown.style.left = '0';
                        dropdown.style.right = 'auto';
                    }
                });

                dropdown.addEventListener('click', function(e) {
                    e.stopPropagation();
                });

                document.addEventListener('click', function() {
                    dropdown.classList.add('d-none');
                });
            }

            initDropdown('filterBtn', 'filterDropdown');

            /* =========================
               FILTER CHANGES
            ========================== */
            if (statusFilter) {
                statusFilter.addEventListener('change', function() {
                    filters.status = this.value || null;
                    filters.page = 1;
                    fetchData(1, filters.startDate, filters.endDate);
                });
            }

            if (categoryFilter) {
                categoryFilter.addEventListener('change', function() {
                    filters.category = this.value || null;
                    filters.page = 1;
                    fetchData(1, filters.startDate, filters.endDate);
                });
            }

            /* =========================
               TANGGAL SEKARANG
            ========================= */
            const monthEl = document.getElementById('currentMonth');

            if (monthEl) {
                const now = new Date();

                monthEl.textContent = now.toLocaleDateString('id-ID', {
                    day: '2-digit',
                    month: 'long',
                    year: 'numeric'
                });
            }

            /* =========================
               FORMAT TANGGAL
            ========================= */
            function formatTanggal(dateString) {

                if (!dateString) return '-';

                const date = new Date(dateString);

                const tanggal = date.toLocaleDateString('id-ID', {
                    day: '2-digit',
                    month: 'long',
                    year: 'numeric'
                });

                const jam = String(date.getHours()).padStart(2, '0');
                const menit = String(date.getMinutes()).padStart(2, '0');

                return `${tanggal}, ${jam}:${menit}`;
            }

            /* =========================
               UPDATE EYE ICON
            ========================== */
            function updateEyeIcon() {
                const checkedCount = document.querySelectorAll(".row-check:checked").length;
                document.querySelectorAll(".eye-action").forEach(el => {
                    el.style.display = checkedCount > 1 ? "none" : "";
                });
            }

            /* =========================
               RESET STATE
            ========================== */
            function resetState() {
                selectedIds = [];
                document.querySelectorAll('.row-check').forEach(cb => cb.checked = false);
                if (checkAll) checkAll.checked = false;
            }

            /* =========================
               CHECK ALL
            ========================== */
            if (checkAll) {
                checkAll.addEventListener("change", function() {
                    document.querySelectorAll(".row-check").forEach(cb => cb.checked = this.checked);
                    updateEyeIcon();
                });
            }

            /* =========================
               SEARCH
            ========================== */
            searchInput?.addEventListener('input', function() {
                const q = this.value.toLowerCase();
                const filtered = allOrders.filter(item =>
                    item.order_number?.toLowerCase().includes(q) ||
                    (item.customer_name || item.user?.name || '')?.toLowerCase().includes(q)
                );

                if (window.innerWidth >= 768) {
                    fetchData(1, filters.startDate, filters.endDate);
                    const orderList = document.getElementById('orderList');
                    if (orderList) orderList.innerHTML = '';
                } else {
                    fetchData(1, filters.startDate, filters.endDate);
                    if (tbody) tbody.innerHTML = '';
                }
            });

            /* =========================
               TABLE CHECK EVENT
            ========================== */
            document.addEventListener("change", function(e) {
                if (e.target.classList && e.target.classList.contains("row-check")) {
                    updateEyeIcon();
                }
            });

            /* =========================
               PAGINATION
            ========================== */
            function renderPagination(pagination) {
                const paginationContainer = document.getElementById('paginationContainer');
                if (!paginationContainer) return;

                const currentPage = pagination.current_page;
                const lastPage = pagination.last_page;

                if (lastPage <= 1) {
                    paginationContainer.innerHTML = '';
                    return;
                }

                let html = '';

                if (currentPage > 1) {
                    html += `<a href="#" data-page="prev" class="nav-text">&lt; Sebelumnya</a>`;
                }

                let startPage = Math.max(1, currentPage - 2);
                let endPage = Math.min(lastPage, currentPage + 2);

                if (startPage > 1) {
                    html += `<a href="#" data-page="1">1</a>`;
                    if (startPage > 2) html += `<span class="dots">...</span>`;
                }

                for (let i = startPage; i <= endPage; i++) {
                    html += `<a href="#" data-page="${i}" ${i === currentPage ? 'class="active"' : ''}>${i}</a>`;
                }

                if (endPage < lastPage) {
                    if (endPage < lastPage - 1) html += `<span class="dots">...</span>`;
                    html += `<a href="#" data-page="${lastPage}">${lastPage}</a>`;
                }

                if (currentPage < lastPage) {
                    html += `<a href="#" data-page="next" class="nav-text">Berikutnya &gt;</a>`;
                }

                paginationContainer.innerHTML = html;
            }

            document.addEventListener('click', function(e) {
                const el = e.target.closest('#paginationContainer a');
                if (!el) return;
                e.preventDefault();
                const page = el.dataset.page;

                if (page === 'prev' && window.currentPage > 1) {
                    fetchData(window.currentPage - 1, filters.startDate, filters.endDate);
                } else if (page === 'next' && window.currentPage < window.lastPage) {
                    fetchData(window.currentPage + 1, filters.startDate, filters.endDate);
                } else if (page && !isNaN(page)) {
                    fetchData(Number(page), filters.startDate, filters.endDate);
                }
            });

            /* =========================
               KALENDER
            ========================== */
            /* =========================
           KALENDER
        ========================== */
            document.addEventListener('dateRangeSelected', function(e) {
                const {
                    start,
                    end
                } = e.detail;
                const el = document.getElementById('currentMonth');
                if (!el) return;

                const parseLocalDate = (str) => {
                    const [y, m, d] = str.split('-');
                    return new Date(y, m - 1, d);
                };

                const formatDate = (date) => {
                    const y = date.getFullYear();
                    const m = String(date.getMonth() + 1).padStart(2, '0');
                    const d = String(date.getDate()).padStart(2, '0');
                    return `${y}-${m}-${d}`;
                };

                const startDate = parseLocalDate(start);
                const endDate = end ? parseLocalDate(end) : null;
                const formatDisplay = (date) => date.toLocaleDateString('id-ID', {
                    day: 'numeric',
                    month: 'short',
                    year: 'numeric'
                });

                el.textContent = endDate ?
                    `${formatDisplay(startDate)} - ${formatDisplay(endDate)}` :
                    formatDisplay(startDate);

                fetchData(
                    1,
                    formatDate(startDate),
                    formatDate(endDate ?? startDate)
                );
            });

            /* =========================
               MAPPING STATUS (FRONTEND ONLY)
            ========================== */
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

            function mapBadgeClass(status, deliveryMethod = null) {
                // Mapping untuk paid
                if (status === 'paid') {
                    return 'badge-paid';
                }

                // Mapping untuk shipped
                if (status === 'shipped') {
                    if (deliveryMethod === 'pickup') {
                        return 'badge-pickup';
                    }
                    return 'badge-shipped';
                }

                // Mapping untuk rejected
                if (status === 'rejected') {
                    return 'badge-rejected';
                }

                // Mapping untuk cancelled
                if (status === 'cancelled') {
                    return 'badge-cancelled';
                }

                // Status lainnya
                const badges = {
                    'awaiting_payment': 'badge-waiting-payment',
                    'completed': 'badge-completed'
                };

                return badges[status] || 'badge-waiting-payment';
            }

            /* =========================
               FETCH DATA
            ========================== */
            function fetchData(page = 1, startDate = null, endDate = null) {
                filters.page = page;
                filters.startDate = startDate;
                filters.endDate = endDate;

                let url = `/api/admin/orders?page=${filters.page}`;

                if (filters.startDate && filters.endDate) {
                    url += `&date_from=${filters.startDate}&date_to=${filters.endDate}`;
                }

                if (filters.status) {
                    url += `&status=${filters.status}`;
                }

                if (filters.search) {
                    url += `&search=${encodeURIComponent(filters.search)}`;
                }

                if (filters.category) {
                    url += `&category=${filters.category}`;
                }

                fetch(url, {
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                        }
                    })
                    .then(res => res.json())
                    .then(res => {
                        const pagination = res.data;
                        const orderList = document.getElementById('orderList');

                        if (!pagination || !Array.isArray(pagination.data) || pagination.data.length === 0) {
                            if (tbody) {
                                tbody.innerHTML = `
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            Data kosong
                        </td>
                    </tr>
                `;
                            }
                            if (orderList) {
                                orderList.innerHTML =
                                    '<p class="text-center text-muted py-3">Tidak ada data ditemukan.</p>';
                            }
                            renderPagination({
                                current_page: 1,
                                last_page: 1
                            });
                            return;
                        }

                        allOrders = pagination.data.sort((a, b) => {
                            const priority = {
                                awaiting_payment: 1,
                                paid: 2,
                                shipped: 3,
                                completed: 4,
                                cancelled: 5,
                                rejected: 6
                            };
                            const statusA = priority[a.status] || 999;
                            const statusB = priority[b.status] || 999;
                            if (statusA !== statusB) return statusA - statusB;
                            return new Date(b.created_at) - new Date(a.created_at);
                        });

                        if (window.innerWidth >= 768) {
                            renderTableBody(allOrders);
                            if (orderList) orderList.innerHTML = '';
                            const tableWrapper = document.querySelector('.table-responsive');
                            if (tableWrapper) tableWrapper.style.display = 'block';
                        } else {
                            renderCardList(allOrders);
                            if (tbody) tbody.innerHTML = '';
                            const tableWrapper = document.querySelector('.table-responsive');
                            if (tableWrapper) tableWrapper.style.display = 'none';
                        }

                        renderPagination(pagination);
                        filters.page = pagination.current_page;

                        resetState();
                        updateEyeIcon();
                    })
                    .catch(err => console.error(err));
            }

            /* =========================
               TABLE BODY
            ========================== */
            function renderTableBody(data) {
                let html = '';
                data.forEach(item => {
                    const deliveryMethod = item.delivery_method || null;
                    const hasNotification = needsNotification(item.status); // Hanya 'paid' yang true
                    const notificationClass = hasNotification ? 'has-notification' : '';

                    html += `
            <tr class="align-middle" data-id="${item.id}">
                <td class="text-center">
                    <input type="checkbox" class="custom-check row-check" value="${item.id}">
                </td>
                <td class="fw-medium">${item.order_number ?? '-'}</td>
               <td>${formatTanggal(item.created_at)}</td>
                <td>${item.customer_name ?? item.user?.name ?? '-'}</td>
                <td class="text-center">
                    <span class="badge ${mapBadgeClass(item.status, deliveryMethod)}">
                        ${mapStatusLabel(item.status, deliveryMethod)}
                    </span>
                </td>
                <td class="fw-semibold">${item.total_format ?? item.formatted_total ?? '-'}</td>
                <td class="text-center">
                    <a href="/admin/order/${item.id}" class="btn-detail-admin text-decoration-none action-icon eye-action ${notificationClass}">
            <span class="iconify" data-icon="heroicons-outline:eye" style="font-size:20px;"></span>
        </a>
                        </td>
                    </tr>
                `;
                });
                document.getElementById('orderTableBody').innerHTML = html;
            }

            /* =========================
               MOBILE CARD
            ========================== */
            function renderCardList(data) {
                const orderList = document.getElementById('orderList');
                if (!orderList) return;

                if (data.length === 0) {
                    orderList.innerHTML = '<p class="text-center text-muted py-3">Tidak ada data ditemukan.</p>';
                    return;
                }

                orderList.innerHTML = data.map(item => {
                    const deliveryMethod = item.delivery_method || null;
                    const hasNotification = needsNotification(item.status); // Hanya 'paid' yang true
                    const notificationClass = hasNotification ? 'has-notification' : '';

                    return `
            <div class="order-card" data-id="${item.id}">
                <input type="checkbox" class="custom-check row-check" value="${item.id}">
                <div class="order-info">
                    <div class="order-row1">
                        <span class="order-number">${item.order_number ?? '-'}</span>
                        <span class="badge ${mapBadgeClass(item.status, deliveryMethod)}">${mapStatusLabel(item.status, deliveryMethod)}</span>
                    </div>
                    <div class="order-row2">
                        <div>
                            <div class="order-customer">${item.customer_name ?? item.user?.name ?? '-'}</div>
                            <div class="order-date">${formatTanggal(item.created_at)}</div>
                        </div>
                        <div class="order-total">${item.total_format ?? item.formatted_total ?? '-'}</div>
                        <div class="order-actions">
                          <a href="/admin/order/${item.id}" class="act-btn detail eye-action ${notificationClass}">
            <span class="iconify" data-icon="heroicons-outline:eye" style="font-size:16px;"></span>
        </a>
                        </div>
                    </div>
                </div>
            </div>
        `;
                }).join('');

                bindCardChecks();
            }

            function bindCardChecks() {
                document.querySelectorAll('#orderList .row-check').forEach(cb => {
                    cb.addEventListener('change', updateSelectLabel);
                });
            }

            function updateSelectLabel() {
                const all = document.querySelectorAll('#orderList .row-check');
                const checked = document.querySelectorAll('#orderList .row-check:checked');
                const label = document.getElementById('selectLabel');
                const checkAllMobile = document.getElementById('checkAllMobile');

                if (label) {
                    label.textContent = checked.length > 0 ?
                        `${checked.length} dari ${all.length} dipilih` : 'Pilih semua';
                }
                if (checkAllMobile) {
                    checkAllMobile.checked = all.length > 0 && checked.length === all.length;
                }
            }

            const checkAllMobile = document.getElementById('checkAllMobile');
            if (checkAllMobile) {
                checkAllMobile.addEventListener('change', function() {
                    document.querySelectorAll('#orderList .row-check')
                        .forEach(cb => cb.checked = this.checked);
                    updateSelectLabel();
                });
            }

            /* =========================
               DELETE
            ========================== */
            deleteBtn?.addEventListener("click", function() {
                selectedIds = Array.from(document.querySelectorAll(".row-check:checked"))
                    .map(cb => cb.value);

                if (selectedIds.length === 0) {
                    showError("Pilih data terlebih dahulu");
                    return;
                }

                new bootstrap.Modal(document.getElementById('deleteModal')).show();
            });

            confirmBtn?.addEventListener("click", function() {
                fetch(`/api/admin/orders/bulk-delete`, {
                        method: "DELETE",
                        headers: {
                            "Content-Type": "application/json",
                            "Accept": "application/json",
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')?.content
                        },
                        body: JSON.stringify({
                            ids: selectedIds
                        })
                    })
                    .then(async res => {
                        const data = await res.json();
                        if (!res.ok || !data.success) {
                            showError(data.message || "Gagal menghapus data");
                            throw new Error(data.message);
                        }
                        return data;
                    })
                    .then(() => {
                        fetchData(window.currentPage);
                        resetState();
                        updateEyeIcon();
                        bootstrap.Modal.getInstance(document.getElementById('deleteModal'))?.hide();
                        setTimeout(() => {
                            showSuccess("Berhasil Menghapus Pesanan");
                        }, 300);
                    })
                    .catch(err => console.error(err));
            });

            function showError(message) {
                const errorModal = document.createElement('div');
                errorModal.className = 'modal fade';
                errorModal.innerHTML = `
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content p-5 text-center">
                            <div class="d-flex justify-content-center mb-3">
                                <div class="d-flex align-items-center justify-content-center rounded-circle" style="width:50px; height:50px; background:#EF4444;">
                                    <i class="iconify text-white fs-1" data-icon="ic:baseline-error"></i>
                                </div>
                            </div>
                            <p class="mb-0 fw-medium">${message}</p>
                        </div>
                    </div>
                `;
                document.body.appendChild(errorModal);
                const modal = new bootstrap.Modal(errorModal);
                modal.show();
                errorModal.addEventListener('hidden.bs.modal', () => errorModal.remove());
            }

            function showSuccess(message) {
                const successModal = new bootstrap.Modal(document.getElementById('deleteSuccessModal'));
                const messageEl = document.querySelector('#deleteSuccessModal .fw-medium');
                if (messageEl) messageEl.textContent = message;
                successModal.show();
                setTimeout(() => successModal.hide(), 2000);
            }

            function needsNotification(status) {
                return status === 'paid';
            }

            fetchData();
        });
    </script>

@endsection
