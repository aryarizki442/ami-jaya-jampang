@extends('backend.app')

@section('title', 'Laporan Penjualan')

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

        /* BADGE STYLES - untuk laporan */
        .badge-report {
            padding: 5px 10px;
            border-radius: 10px;
            font-size: 12px;
            font-weight: 500;
        }

        /* MOBILE FIX */
        @media (max-width: 576px) {
            .custom-pagination {
                justify-content: space-between;
            }

            .custom-pagination .page-number {
                display: none;
            }

            .custom-pagination .page-number.active {
                display: inline-block;
                font-weight: bold;
            }

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

        /* Total Pendapatan Card */
        .total-revenue-card {
            background: linear-gradient(135deg, #0D3523, #269B66);
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 20px;
            color: white;
        }

        .total-revenue-card h4 {
            font-size: 14px;
            opacity: 0.9;
            margin-bottom: 8px;
        }

        .total-revenue-card h2 {
            font-size: 28px;
            font-weight: 700;
            margin: 0;
        }

        .total-revenue-card small {
            font-size: 12px;
            opacity: 0.8;
        }
    </style>

    <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
        <button id="openCalendar" class="btn btn-main btn-sm px-3 d-flex align-items-center gap-2">
            <span class="iconify" data-icon="uil:calendar" style="font-size:18px;"></span>
            <span id="currentMonth"></span>
        </button>
        @include('backend.components.calendar')
    </div>

    {{-- Total Pendapatan --}}


    <div class="card border-0 shadow-sm rounded-4 p-4">

        <div class="row g-2 align-items-center mb-3">

            <!-- SEARCH -->
            <div class="col-12 col-md">
                <div class="position-relative">
                    <input type="text" id="searchInput" class="form-control pe-5" placeholder="Cari Laporan disini">
                    <i class="ri-search-line position-absolute top-50 end-0 translate-middle-y me-3 text-muted"></i>
                </div>
            </div>

            <!-- EXPORT BUTTON -->
            {{-- <div class="col-6 col-md-auto">
                <button class="btn btn-success w-100 d-flex align-items-center justify-content-center gap-1" id="exportBtn">
                    <span class="iconify" data-icon="mdi:file-excel"></span>
                    Export
                </button>
            </div> --}}

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
        <div class="cat-list" id="reportList"></div>

        <!-- TABLE -->
        <div class="table-responsive">
            <table class="table align-middle custom-table table-hover">
                <thead>
                    <tr class="text-center align-middle small">
                        <th style="width:40px;">
                            <input type="checkbox" id="checkAll" class="custom-check">
                        </th>
                        <th class="text-start">Nomor</th>
                        <th class="text-start">Nama Produk</th>
                        <th class="text-center">Terjual</th>
                        <th class="text-start">Tanggal</th>
                        <th class="text-end">Pendapatan</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="reportTableBody"></tbody>
            </table>
        </div>

        <!-- PAGINATION -->
        <div class="custom-pagination" id="pagination"></div>

    </div>

    <script src="https://code.iconify.design/3/3.1.0/iconify.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            /* =========================
               DATA DUMMY (STATIS)
            ========================== */
            const dummyData = [{
                    id: 1,
                    nomor: 1,
                    nama_produk: "Beras Putih Rojo Lele",
                    terjual: 120,
                    tanggal: "Maret 2026",
                    pendapatan: 12000000
                },
                {
                    id: 2,
                    nomor: 2,
                    nama_produk: "Beras Putih Ramos",
                    terjual: 115,
                    tanggal: "Maret 2026",
                    pendapatan: 11500000
                },
                {
                    id: 3,
                    nomor: 3,
                    nama_produk: "Beras Putih Pandan Wangi",
                    terjual: 110,
                    tanggal: "Maret 2026",
                    pendapatan: 11000000
                },
                {
                    id: 4,
                    nomor: 4,
                    nama_produk: "Beras Merah Rojo Lele",
                    terjual: 100,
                    tanggal: "Maret 2026",
                    pendapatan: 10000000
                },
                {
                    id: 5,
                    nomor: 5,
                    nama_produk: "Beras Ketan Rojo Lele",
                    terjual: 95,
                    tanggal: "Maret 2026",
                    pendapatan: 9500000
                },
                {
                    id: 6,
                    nomor: 6,
                    nama_produk: "Beras Putih Rojo Lele",
                    terjual: 90,
                    tanggal: "Maret 2026",
                    pendapatan: 9000000
                },
                {
                    id: 7,
                    nomor: 7,
                    nama_produk: "Beras Ketan Pandan Wangi",
                    terjual: 85,
                    tanggal: "Maret 2026",
                    pendapatan: 8500000
                },
                {
                    id: 8,
                    nomor: 8,
                    nama_produk: "Beras Merah Rojo Lele",
                    terjual: 80,
                    tanggal: "Maret 2026",
                    pendapatan: 8000000
                },
                {
                    id: 9,
                    nomor: 9,
                    nama_produk: "Beras Putih BMW",
                    terjual: 75,
                    tanggal: "Maret 2026",
                    pendapatan: 7500000
                },
                {
                    id: 10,
                    nomor: 10,
                    nama_produk: "Beras Putih BMW",
                    terjual: 70,
                    tanggal: "Maret 2026",
                    pendapatan: 7000000
                }
            ];

            let allData = [...dummyData];
            let filteredData = [...allData];
            let currentPage = 1;
            const rowsPerPage = 5;

            /* =========================
               ELEMENTS
            ========================== */
            const checkAll = document.getElementById('checkAll');
            const checkAllMobile = document.getElementById('checkAllMobile');
            const searchInput = document.getElementById("searchInput");
            const tbody = document.getElementById('reportTableBody');
            const reportList = document.getElementById('reportList');
            const deleteBtn = document.getElementById("deleteSelected");
            const confirmBtn = document.getElementById("confirmDeleteBtn");
            const exportBtn = document.getElementById("exportBtn");
            const totalRevenueEl = document.getElementById("totalRevenue");

            /* =========================
               FORMAT RUPIAH
            ========================== */
            function formatRupiah(angka) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                }).format(angka);
            }

            /* =========================
               HITUNG TOTAL PENDAPATAN
            ========================== */
            function updateTotalRevenue() {
                const total = filteredData.reduce((sum, item) => sum + item.pendapatan, 0);
                totalRevenueEl.innerHTML = formatRupiah(total);
            }

            /* =========================
               RENDER TABLE BODY (DESKTOP)
            ========================== */
            function renderTableBody() {
                if (!tbody) return;

                const start = (currentPage - 1) * rowsPerPage;
                const end = start + rowsPerPage;
                const pageData = filteredData.slice(start, end);

                if (pageData.length === 0) {
                    tbody.innerHTML = `
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            Tidak ada data ditemukan
                        </td>
                    </tr>
                `;
                    return;
                }

                let html = '';
                pageData.forEach(item => {
                    html += `
                    <tr class="align-middle" data-id="${item.id}">
                        <td class="text-center">
                            <input type="checkbox" class="custom-check row-check" value="${item.id}">
                        </td>
                        <td class="text-start">${item.nomor}</td>
                        <td class="fw-medium text-start">${item.nama_produk}</td>
                        <td class="text-center">${item.terjual}</td>
                        <td class="text-start">${item.tanggal}</td>
                        <td class="text-end fw-semibold">${formatRupiah(item.pendapatan)}</td>
                        <td class="text-center">
                            <a href="/admin/report/detail/${item.id}"
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
                tbody.innerHTML = html;

                // Event listener untuk tombol detail
                document.querySelectorAll('.btn-detail').forEach(btn => {
                    btn.addEventListener('click', function(e) {
                        e.preventDefault();
                        const id = this.dataset.id;
                        const item = allData.find(i => i.id == id);
                        if (item) {
                            alert(
                                `Detail Laporan\n\nProduk: ${item.nama_produk}\nTerjual: ${item.terjual}\nPendapatan: ${formatRupiah(item.pendapatan)}\nTanggal: ${item.tanggal}`
                            );
                        }
                    });
                });
            }

            /* =========================
               RENDER CARD LIST (MOBILE)
            ========================== */
            function renderCardList() {
                if (!reportList) return;

                const start = (currentPage - 1) * rowsPerPage;
                const end = start + rowsPerPage;
                const pageData = filteredData.slice(start, end);

                if (pageData.length === 0) {
                    reportList.innerHTML = `
                    <div class="text-center text-muted py-3">
                        Tidak ada data ditemukan
                    </div>
                `;
                    return;
                }

                let html = '';
                pageData.forEach(item => {
                    html += `
                    <div class="cat-card" data-id="${item.id}">
                        <input type="checkbox" class="custom-check row-check" value="${item.id}">
                        <div class="cat-info">
                            <div class="cat-row1">
                                <span class="cat-name fw-semibold">${item.nama_produk}</span>
                                <span style="font-size:11px; color:#666;">#${item.nomor}</span>
                            </div>
                            <div class="cat-row2 mb-1">
                                <span style="font-size:12px;">Terjual: ${item.terjual}</span>
                                <span style="font-size:12px; font-weight:500;">${formatRupiah(item.pendapatan)}</span>
                            </div>
                            <div class="cat-row2">
                                <span style="font-size:11px; color:#888;">${item.tanggal}</span>
                                <div class="cat-actions">
                                    <a href="/admin/report/detail/${item.id}" class="btn-detail-admin text-decoration-none action-icon eye-action" data-id="${item.id}">
                                        <span class="iconify" data-icon="heroicons-outline:eye" style="font-size:16px;"></span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                });
                reportList.innerHTML = html;

                // Event listener untuk tombol detail mobile
                document.querySelectorAll('.btn-detail-mobile').forEach(btn => {
                    btn.addEventListener('click', function(e) {
                        e.preventDefault();
                        const id = this.dataset.id;
                        const item = allData.find(i => i.id == id);
                        if (item) {
                            alert(
                                `Detail Laporan\n\nProduk: ${item.nama_produk}\nTerjual: ${item.terjual}\nPendapatan: ${formatRupiah(item.pendapatan)}\nTanggal: ${item.tanggal}`
                            );
                        }
                    });
                });
            }

            /* =========================
               RENDER PAGINATION
            ========================== */
            function renderPagination() {
                const paginationEl = document.getElementById('pagination');
                if (!paginationEl) return;

                const totalPages = Math.ceil(filteredData.length / rowsPerPage);

                if (totalPages <= 1) {
                    paginationEl.innerHTML = '';
                    return;
                }

                let html = '';

                // Prev button
                html +=
                    `<a href="#" class="nav-text ${currentPage === 1 ? 'disabled' : ''}" data-page="prev" ${currentPage === 1 ? 'style="opacity:0.5; pointer-events:none;"' : ''}>« Sebelumnya</a>`;

                // Page numbers
                for (let i = 1; i <= totalPages; i++) {
                    if (totalPages <= 7 || i === 1 || i === totalPages || (i >= currentPage - 1 && i <=
                            currentPage + 1)) {
                        html +=
                            `<a href="#" class="page-number ${i === currentPage ? 'active' : ''}" data-page="${i}">${i}</a>`;
                    } else if (i === currentPage - 2 || i === currentPage + 2) {
                        html += `<span class="page-number">...</span>`;
                    }
                }

                // Next button
                html +=
                    `<a href="#" class="nav-text ${currentPage === totalPages ? 'disabled' : ''}" data-page="next" ${currentPage === totalPages ? 'style="opacity:0.5; pointer-events:none;"' : ''}>Berikutnya »</a>`;

                paginationEl.innerHTML = html;

                // Event listeners
                paginationEl.querySelectorAll('a').forEach(link => {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        if (this.classList.contains('disabled')) return;

                        const page = this.dataset.page;
                        if (page === 'prev' && currentPage > 1) {
                            currentPage--;
                        } else if (page === 'next' && currentPage < totalPages) {
                            currentPage++;
                        } else if (!isNaN(parseInt(page))) {
                            currentPage = parseInt(page);
                        } else {
                            return;
                        }

                        renderView();
                    });
                });
            }

            /* =========================
               RENDER VIEW (TABEL/CARD)
            ========================== */
            function renderView() {
                if (window.innerWidth >= 768) {
                    // Desktop mode
                    if (tbody) tbody.innerHTML = '<tr><td colspan="7" class="text-center">Memuat...</td></tr>';
                    if (reportList) reportList.style.display = 'none';
                    if (document.querySelector('.table-responsive')) document.querySelector('.table-responsive')
                        .style.display = 'block';

                    renderTableBody();
                    renderPagination();
                } else {
                    // Mobile mode
                    if (reportList) {
                        reportList.style.display = 'flex';
                        reportList.innerHTML = '<div class="text-center py-3">Memuat...</div>';
                    }
                    if (document.querySelector('.table-responsive')) document.querySelector('.table-responsive')
                        .style.display = 'none';

                    renderCardList();
                    renderPagination();
                }

                // Update checkAll state
                updateCheckAllState();
                updateTotalRevenue();
            }

            /* =========================
               UPDATE CHECKBOX STATE
            ========================== */
            function updateCheckAllState() {
                const checkboxes = document.querySelectorAll('.row-check');
                const checkedBoxes = document.querySelectorAll('.row-check:checked');

                if (checkAll) checkAll.checked = checkboxes.length > 0 && checkedBoxes.length === checkboxes.length;
                if (checkAllMobile) checkAllMobile.checked = checkboxes.length > 0 && checkedBoxes.length ===
                    checkboxes.length;

                const selectLabel = document.getElementById('selectLabel');
                if (selectLabel && window.innerWidth < 768) {
                    selectLabel.textContent = checkedBoxes.length > 0 ?
                        `${checkedBoxes.length} dari ${checkboxes.length} dipilih` : 'Pilih semua';
                }
            }

            /* =========================
               SEARCH FUNCTION
            ========================== */
            function handleSearch() {
                const keyword = searchInput.value.toLowerCase();

                if (keyword === '') {
                    filteredData = [...allData];
                } else {
                    filteredData = allData.filter(item =>
                        item.nama_produk.toLowerCase().includes(keyword) ||
                        item.tanggal.toLowerCase().includes(keyword) ||
                        item.nomor.toString().includes(keyword)
                    );
                }

                currentPage = 1;
                renderView();
            }

            /* =========================
               CHECK ALL FUNCTION
            ========================== */
            function handleCheckAll(checkbox) {
                document.querySelectorAll('.row-check').forEach(cb => {
                    cb.checked = checkbox.checked;
                });
                updateCheckAllState();
            }

            /* =========================
               DELETE SELECTED
            ========================== */
            function deleteSelected() {
                const selectedIds = Array.from(document.querySelectorAll('.row-check:checked')).map(cb => parseInt(
                    cb.value));

                if (selectedIds.length === 0) {
                    alert('Pilih data terlebih dahulu');
                    return;
                }

                if (confirm(`Apakah Anda yakin ingin menghapus ${selectedIds.length} data laporan?`)) {
                    allData = allData.filter(item => !selectedIds.includes(item.id));

                    // Reset nomor urut
                    allData.forEach((item, index) => {
                        item.nomor = index + 1;
                    });

                    filteredData = allData.filter(item => {
                        const keyword = searchInput.value.toLowerCase();
                        return keyword === '' ||
                            item.nama_produk.toLowerCase().includes(keyword) ||
                            item.tanggal.toLowerCase().includes(keyword);
                    });

                    if (currentPage > Math.ceil(filteredData.length / rowsPerPage) && currentPage > 1) {
                        currentPage = Math.max(1, Math.ceil(filteredData.length / rowsPerPage));
                    }

                    renderView();
                    alert('Data berhasil dihapus');
                }
            }

            /* =========================
               EXPORT TO CSV
            ========================== */
            function exportToCSV() {
                let dataToExport = filteredData;

                if (dataToExport.length === 0) {
                    alert('Tidak ada data untuk diekspor');
                    return;
                }

                const headers = ['No', 'Nama Produk', 'Terjual', 'Tanggal', 'Pendapatan'];
                const rows = dataToExport.map(item => [
                    item.nomor,
                    item.nama_produk,
                    item.terjual,
                    item.tanggal,
                    formatRupiah(item.pendapatan)
                ]);

                const csvContent = [headers, ...rows].map(row => row.join(',')).join('\n');
                const blob = new Blob([csvContent], {
                    type: 'text/csv;charset=utf-8;'
                });
                const link = document.createElement('a');
                const url = URL.createObjectURL(blob);

                link.setAttribute('href', url);
                link.setAttribute('download', `laporan_penjualan_${new Date().toISOString().split('T')[0]}.csv`);
                link.style.visibility = 'hidden';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                URL.revokeObjectURL(url);
            }

            /* =========================
               KALENDER / DATE RANGE
            ========================== */
            document.addEventListener('dateRangeSelected', function(e) {
                const {
                    start,
                    end
                } = e.detail;
                const monthEl = document.getElementById('currentMonth');

                if (monthEl) {
                    const startDate = new Date(start);
                    const endDate = end ? new Date(end) : new Date(start);
                    monthEl.textContent =
                        `${startDate.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })} - ${endDate.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })}`;
                }

                // Filter dummy data by date range (simulasi)
                if (start && end) {
                    // Untuk dummy, hanya filter berdasarkan bulan/tahun
                    const startYearMonth = start.substring(0, 7);
                    const endYearMonth = end.substring(0, 7);
                    filteredData = allData.filter(item => {
                        const itemDate = item.tanggal;
                        return true; // Simulasi, karena dummy data semua Maret 2026
                    });
                } else {
                    filteredData = [...allData];
                }

                currentPage = 1;
                renderView();
            });

            /* =========================
               INIT DROPDOWN
            ========================== */
            function initDropdown(triggerId, dropdownId) {
                const trigger = document.getElementById(triggerId);
                const dropdown = document.getElementById(dropdownId);
                if (!trigger || !dropdown) return;

                trigger.addEventListener('click', function(e) {
                    e.stopPropagation();
                    dropdown.classList.toggle('d-none');
                });

                dropdown?.addEventListener('click', function(e) {
                    e.stopPropagation();
                });

                document.addEventListener('click', function() {
                    dropdown?.classList.add('d-none');
                });
            }

            initDropdown('filterBtn', 'filterDropdown');

            /* =========================
               EVENT LISTENERS
            ========================== */
            if (searchInput) searchInput.addEventListener('input', handleSearch);
            if (checkAll) checkAll.addEventListener('change', function() {
                handleCheckAll(this);
            });
            if (checkAllMobile) checkAllMobile.addEventListener('change', function() {
                handleCheckAll(this);
            });
            if (deleteBtn) deleteBtn.addEventListener('click', deleteSelected);
            if (exportBtn) exportBtn.addEventListener('click', exportToCSV);

            // Update checkbox when clicking on row/card
            document.addEventListener('change', function(e) {
                if (e.target.classList && e.target.classList.contains('row-check')) {
                    updateCheckAllState();
                }
            });

            // Handle window resize
            window.addEventListener('resize', function() {
                renderView();
            });

            // Set current month display
            const monthEl = document.getElementById('currentMonth');
            if (monthEl) {
                const now = new Date();
                monthEl.textContent = now.toLocaleDateString('id-ID', {
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric'
                });
            }

            // Initial render
            renderView();
        });
    </script>

@endsection
