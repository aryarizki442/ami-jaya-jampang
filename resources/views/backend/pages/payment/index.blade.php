@extends('backend.app')

@section('title', 'Pembayaran')

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
        .btn-add-full {
            width: 100%;
            padding: 9px 12px;
            margin-bottom: 12px;
            background: linear-gradient(90deg, #0D3523, #269B66);
            border: none;
            border-radius: 8px;
            color: #fff;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            display: none;
            align-items: center;
            justify-content: center;
            gap: 6px;
            text-decoration: none;
        }

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

        .cat-list {
            display: none;
            flex-direction: column;
            gap: 10px;
        }

        .cat-card {
            background: #fff;
            border: 1px solid #E8E8E9;
            border-radius: 12px;
            padding: 12px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
            transition: border-color 0.15s;
        }

        .cat-card:hover {
            border-color: #b0b0b0;
            cursor: pointer;
        }

        .cat-img {
            width: 52px;
            height: 52px;
            border-radius: 8px;
            object-fit: cover;
            flex-shrink: 0;
            background: #f0f0f0;
        }

        .cat-info {
            flex: 1;
            min-width: 0;
        }

        .cat-row1 {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 6px;
            margin-bottom: 6px;
        }

        .cat-name {
            font-size: 13px;
            font-weight: 500;
            color: #1a1a1a;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .cat-row2 {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .cat-actions {
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

        .act-btn.edit:hover {
            color: #185FA5;
            border-color: #185FA5;
            background: #E6F1FB;
        }

        .act-btn.detail:hover {
            color: #3B6D11;
            border-color: #3B6D11;
            background: #EAF3DE;
        }

        .custom-pagination {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            align-items: center;
        }

        /* DESKTOP NORMAL */
        .custom-pagination .page-number {
            display: inline-block;
        }

        /* MOBILE FIX */
        @media (max-width: 576px) {

            .custom-pagination {
                justify-content: space-between;
            }

            /* sembunyikan semua nomor */
            .custom-pagination .page-number {
                display: none;
            }

            /* tampilkan hanya yang aktif */
            .custom-pagination .page-number.active {
                display: inline-block;
                font-weight: bold;
            }

            /* prev & next tetap tampil */
            .custom-pagination .nav-text {
                display: inline-block;
            }
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {

            .table-responsive {
                display: none !important;
            }

            .btn-add-full {
                display: flex;
            }

            .select-all-row {
                display: flex;
            }

            .cat-list {
                display: flex;
            }

            .btn-desktop-add {
                display: none !important;
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

                <!-- DROPDOWN -->
                <div id="filterDropdown" class="position-absolute bg-white shadow rounded p-3 mt-2 d-none"
                    style="min-width:220px; z-index:999;">

                    <div class="fw-semibold mb-2">Filter</div>

                    <div class="small text-muted mb-1">Status Pembayaran</div>

                    <select id="statusFilter" class="form-select form-select-sm">

                        <option value="" data-filter="all">
                            Semua Status
                        </option>

                        @foreach ($paymentStatuses as $value => $label)
                            <option value="{{ $value }}" data-filter="{{ strtolower($label) }}">
                                {{ $label }}
                            </option>
                        @endforeach

                    </select>

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
        <div class="payment-list" id="paymentList"></div>

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
                        <th class="text-center">Status Pembayaran</th>
                        <th class="text-center">Metode Pembayaran</th>
                        <th class="text-center">Aksi</th>

                    </tr>
                </thead>

                <tbody id="PaymentTableBody">

                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            Memuat data...
                        </td>
                    </tr>

                </tbody>

            </table>
        </div>

        <!-- PAGINATION -->
        <div class="custom-pagination"></div>

    </div>

    <script src="https://code.iconify.design/3/3.1.0/iconify.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            /* =========================
               ELEMENTS
            ========================== */
            const checkAll = document.getElementById('checkAll');
            const searchInput = document.getElementById("searchInput");

            const tbody = document.getElementById('PaymentTableBody');

            const deleteBtn = document.getElementById("deleteSelected");
            const confirmBtn = document.getElementById("confirmDeleteBtn");

            const statusFilter = document.getElementById('statusFilter');

            let selectedIds = [];
            let allPayments = [];

            let filters = {
                page: 1,
                startDate: null,
                endDate: null,
                status: null
            };

            /* =========================
               DROPDOWN
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
               STATUS FILTER
            ========================== */
            if (statusFilter) {

                statusFilter.addEventListener('change', function() {

                    filters.status = this.value || null;
                    filters.page = 1;

                    fetchData();
                });

                statusFilter.dispatchEvent(new Event('change'));
            }

            /* =========================
               TANGGAL SEKARANG
            ========================== */
            const monthEl = document.getElementById('currentMonth');

            if (monthEl) {

                const now = new Date();

                monthEl.textContent = now.toLocaleDateString('id-ID', {
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric'
                });
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

                document.querySelectorAll('.row-check')
                    .forEach(cb => cb.checked = false);

                if (checkAll) {
                    checkAll.checked = false;
                }
            }

            /* =========================
               CHECK ALL
            ========================== */
            if (checkAll) {

                checkAll.addEventListener("change", function() {

                    document.querySelectorAll(".row-check")
                        .forEach(cb => cb.checked = this.checked);

                    updateEyeIcon();
                });
            }

            /* =========================
               SEARCH
            ========================== */
            searchInput?.addEventListener('input', function() {

                const q = this.value.toLowerCase();

                const filtered = allPayments.filter(item =>
                    item.order_number?.toLowerCase().includes(q) ||
                    item.customer_name?.toLowerCase().includes(q)
                );

                renderTableBody(filtered);
                renderCardList(filtered);
            });

            /* =========================
               TABLE CHECK EVENT
            ========================== */
            tbody?.addEventListener("change", function(e) {

                if (e.target.classList.contains("row-check")) {
                    updateEyeIcon();
                }
            });

            /* =========================
               PAGINATION
            ========================== */
            document.addEventListener('click', function(e) {

                const el = e.target.closest('.custom-pagination a');

                if (!el) return;

                const page = el.dataset.page;

                if (!page) return;

                e.preventDefault();

                if (page === 'prev') {

                    if (window.currentPage > 1) {
                        fetchData(window.currentPage - 1);
                    }

                } else if (page === 'next') {

                    if (window.currentPage < window.lastPage) {
                        fetchData(window.currentPage + 1);
                    }

                } else {

                    fetchData(Number(page));
                }
            });

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

                const formatDisplay = (date) =>
                    date.toLocaleDateString('id-ID', {
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
               FETCH DATA
            ========================== */
            function fetchData(page = 1, startDate = null, endDate = null) {

                filters.page = page;
                filters.startDate = startDate;
                filters.endDate = endDate;

                let url = `/api/admin/payments?page=${filters.page}`;

                if (filters.startDate && filters.endDate) {
                    url += `&start_date=${filters.startDate}&end_date=${filters.endDate}`;
                }

                if (filters.status) {
                    url += `&status=${filters.status}`;
                }

                fetch(url, {
                        headers: {
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(res => {

                        const pagination = res.data;

                        const tbody = document.getElementById('PaymentTableBody');
                        const paymentList = document.getElementById('paymentList');

                        if (!pagination || !Array.isArray(pagination.data) || pagination.data.length === 0) {

                            tbody.innerHTML = `
                            <tr>
                                <td colspan="7" class="text-center text-muted">
                                    Data kosong
                                </td>
                            </tr>
                        `;
                            return;
                        }

                        allPayments = pagination.data;

                        // Desktop
                        if (window.innerWidth >= 768) {

                            renderTableBody(pagination.data);

                            // kosongkan mobile list
                            paymentList.innerHTML = '';

                        } else {

                            // Mobile
                            renderCardList(pagination.data);

                            // kosongkan table
                            tbody.innerHTML = '';
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

                    html += `
                    <tr class="align-middle" data-id="${item.id}">

                        <td class="text-center">
                            <input type="checkbox"
                                class="custom-check row-check"
                                value="${item.id}">
                        </td>

                        <td class="fw-medium">
                            ${item.order_number ?? '-'}
                        </td>

                        <td>
                            ${item.created_at ?? '-'}
                        </td>

                        <td>
                            ${item.customer_name ?? '-'}
                        </td>

                        <td class="text-center">
                            <span class="badge ${item.status_class}">
                                ${item.status_label}
                            </span>
                        </td>

                        <td class="text-center">
                            ${item.payment_method ?? '-'}
                        </td>

                        <td class="text-center">

                            <a href="/admin/payment/detail/${item.id}"
                                class="btn-detail-admin text-decoration-none action-icon eye-action">

                                <span class="iconify"
                                    data-icon="heroicons-outline:eye"
                                    style="font-size:20px;">
                                </span>

                            </a>

                        </td>

                    </tr>
                `;
                });

                document.getElementById('PaymentTableBody').innerHTML = html;
            }

            /* =========================
               MOBILE CARD
            ========================== */
            function renderCardList(data) {

                const paymentList = document.getElementById('paymentList');

                if (!paymentList) return;

                if (data.length === 0) {

                    paymentList.innerHTML = `
                    <p class="text-center text-muted py-3">
                        Tidak ada data ditemukan.
                    </p>
                `;

                    return;
                }

                paymentList.innerHTML = data.map(item => {

                    return `
                    <div class="cat-card" data-id="${item.id}">

                        <input type="checkbox"
                            class="custom-check row-check"
                            value="${item.id}">

                        <div class="cat-info">

                            <div class="cat-row1">

                                <span class="cat-name">
                                    ${item.order_number ?? '-'}
                                </span>

                                <span class="badge ${item.status_class}">
                                    ${item.status_label}
                                </span>

                            </div>

                            <div class="cat-row2">

                                <span style="font-size:12px;">
                                    ${item.customer_name ?? '-'}
                                </span>

                                <span style="font-size:12px;">
                                    ${item.payment_method ?? '-'}
                                </span>

                                <div class="cat-actions">

                                    <a href="/admin/payment/detail/${item.id}"
                                        class="act-btn detail">

                                        <span class="iconify"
                                            data-icon="heroicons-outline:eye"
                                            style="font-size:16px;">
                                        </span>

                                    </a>

                                </div>

                            </div>

                        </div>

                    </div>
                `;
                }).join('');

                bindCardChecks();
            }

            /* =========================
               MOBILE CHECKBOX
            ========================== */
            function bindCardChecks() {

                document.querySelectorAll('#paymentList .row-check').forEach(cb => {

                    cb.addEventListener('change', updateSelectLabel);
                });
            }

            function updateSelectLabel() {

                const all = document.querySelectorAll('#paymentList .row-check');

                const checked = document.querySelectorAll('#paymentList .row-check:checked');

                const label = document.getElementById('selectLabel');

                const checkAllMobile = document.getElementById('checkAllMobile');

                if (label) {

                    label.textContent = checked.length > 0 ?
                        `${checked.length} dari ${all.length} dipilih` :
                        'Pilih semua';
                }

                if (checkAllMobile) {

                    checkAllMobile.checked =
                        all.length > 0 && checked.length === all.length;
                }
            }

            const checkAllMobile = document.getElementById('checkAllMobile');

            if (checkAllMobile) {

                checkAllMobile.addEventListener('change', function() {

                    document.querySelectorAll('#paymentList .row-check')
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

                new bootstrap.Modal(
                    document.getElementById('deleteModal')
                ).show();
            });

            confirmBtn?.addEventListener("click", function() {

                fetch(`/api/admin/payments/bulk-delete`, {

                        method: "DELETE",

                        headers: {
                            "Content-Type": "application/json",
                            "Accept": "application/json",
                            "X-CSRF-TOKEN": document
                                .querySelector('meta[name="csrf-token"]')
                                .content
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

                        bootstrap.Modal.getInstance(
                            document.getElementById('deleteModal')
                        )?.hide();

                        setTimeout(() => {

                            showSuccess("Berhasil Menghapus Pembayaran");

                        }, 300);
                    })

                    .catch(err => console.error(err));
            });

            fetchData();
        });
    </script>

@endsection
