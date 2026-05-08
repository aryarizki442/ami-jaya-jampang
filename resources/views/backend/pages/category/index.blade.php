@extends('backend.app')

@section('title', 'Kategori')

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
            width: max-content;
            min-width: 100%;
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

    {{-- Tombol Tambah (mobile only, full-width) --}}
    <a href="{{ route('admin.category.create') }}" class="btn-add-full">
        Tambah Kategori <i class="ri-add-line"></i>
    </a>

    {{-- TOP (desktop) --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <div></div>
        <a href="{{ route('admin.category.create') }}"
            class="btn btn-main btn-sm d-flex align-items-center gap-2 btn-desktop-add">
            Tambah Kategori
            <i class="ri-add-line"></i>
        </a>
    </div>

    {{-- CARD --}}
    <div class="card shadow-sm p-3 p-md-4">

        {{-- SEARCH + DELETE --}}
        <div class="row g-2 align-items-center mb-3">
            <div class="col-12 col-md">
                <div class="position-relative">
                    <input type="text" id="searchInput" class="form-control pe-5" placeholder="Cari Kategori disini">
                    <i class="ri-search-line position-absolute top-50 end-0 translate-middle-y me-3 text-muted"></i>
                </div>
            </div>
            <div class="col-12 col-md-auto">
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
        <div class="cat-list" id="catList"></div>

        {{-- TABLE (desktop) --}}
        <div class="table-responsive">
            <table class="table align-middle custom-table table-hover">
                <thead>
                    <tr class="text-center align-middle small">
                        <th style="width:40px;">
                            <input type="checkbox" id="checkAll" class="custom-check">
                        </th>
                        <th class="text-start">Nomor</th>
                        <th class="text-start">Nama Kategori</th>
                        <th class="text-start">Status</th>
                        <th>Gambar Kategori</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="categoryTableBody"></tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        <div class="custom-pagination"></div>
    </div>

    <!-- MODAL DELETE -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header justify-content-center">
                    <h5 class="fw-semibold m-0">Hapus Kategori</h5>
                </div>

                <div class="modal-body text-center">
                    <p class="mb-0">Apakah Anda yakin ingin menghapus item yang dipilih?</p>
                </div>

                <div class="modal-footer justify-content-center gap-2">
                    <button class="btn btn-delete-second" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-delete-main text-custom-red" id="confirmDeleteBtn">Hapus</button>
                </div>

            </div>
        </div>
    </div>
    <div class="modal fade" id="deleteSuccessModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content p-5 text-center">

                <!-- ICON SUCCESS -->
                <div class="d-flex justify-content-center mb-3">
                    <div class="d-flex align-items-center justify-content-center rounded-circle"
                        style="width:50px; height:50px; background:#22C55E;">

                        <i class="iconify text-white fs-1" data-icon="iconamoon:check-bold"></i>

                    </div>
                </div>

                <p class="mb-0 fw-medium">Berhasil Menghapus Kategori</p>

            </div>
        </div>
    </div>

    <!-- UNIVERSAL SUCCESS MODAL -->
    <div class="modal fade" id="successModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content p-5 text-center">

                <div class="d-flex justify-content-center mb-3">
                    <div class="d-flex align-items-center justify-content-center rounded-circle"
                        style="width:50px; height:50px; background:#22C55E;">

                        <i class="iconify text-white fs-1" data-icon="iconamoon:check-bold"></i>

                    </div>
                </div>

                <p class="mb-0 fw-medium" id="successMessage">
                    Berhasil
                </p>

            </div>
        </div>
    </div>

    {{-- MODAL EROR --}}
    <div class="modal fade" id="errorModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content p-5 text-center">

                <div class="d-flex justify-content-center mb-3">
                    <div class="d-flex align-items-center justify-content-center rounded-circle"
                        style="width:50px; height:50px; background:#EF4444;">

                        <i class="iconify text-white fs-1" data-icon="iconamoon:close-bold"></i>
                    </div>
                </div>

                <p class="mb-0 fw-medium" id="errorMessage">Terjadi kesalahan</p>

            </div>
        </div>
    </div>



    <script src="https://code.iconify.design/3/3.1.0/iconify.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            /* =========================
               ELEMENTS
            ========================== */
            const urlParams = new URLSearchParams(window.location.search);

            const dropdown = document.getElementById('kategoriDropdown');
            const icon = document.getElementById('dropdownIcon');

            const checkAll = document.getElementById('checkAll');
            const searchInput = document.getElementById("searchInput");
            const tbody = document.getElementById('categoryTableBody');

            const deleteBtn = document.getElementById("deleteSelected");
            const confirmBtn = document.getElementById("confirmDeleteBtn");

            let selectedIds = [];
            let allCategories = [];

            /* =========================
               SUCCESS HANDLER (CREATE & UPDATE)
            ========================== */
            const created = urlParams.get('created') === '1';
            const updated = urlParams.get('updated') === '1';
            const id = urlParams.get('id');

            if (created || updated) {

                let message = created ?
                    'Berhasil Menambahkan Kategori' :
                    'Berhasil Mengubah Kategori';

                showSuccess(message);

                if (id) {
                    setTimeout(() => {
                        const row = document.querySelector(`[data-id='${id}']`);
                        if (row) {
                            row.classList.add('table-success');
                            row.scrollIntoView({
                                behavior: 'smooth',
                                block: 'center'
                            });

                            setTimeout(() => {
                                row.classList.remove('table-success');
                            }, 2000);
                        }
                    }, 200);
                }

                window.history.replaceState({}, document.title, window.location.pathname);
            }

            // =========================
            // FUNCTION MODAL Create & Update || EROR
            // =========================
            function showSuccess(message) {
                const text = document.getElementById('successMessage');
                text.innerText = message;

                const modal = new bootstrap.Modal(document.getElementById('successModal'));
                modal.show();

                setTimeout(() => modal.hide(), 1500);
            }

            function showError(message) {
                const text = document.getElementById('errorMessage');
                text.innerText = message;

                const modal = new bootstrap.Modal(document.getElementById('errorModal'));
                modal.show();

                setTimeout(() => modal.hide(), 2000);
            }

            /* =========================
               DROPDOWN ICON TOGGLE
            ========================== */
            if (dropdown && icon) {
                dropdown.addEventListener('show.bs.dropdown', () => {
                    icon.setAttribute('data-icon', 'iconamoon:arrow-up-2-light');
                });

                dropdown.addEventListener('hide.bs.dropdown', () => {
                    icon.setAttribute('data-icon', 'iconamoon:arrow-down-2-light');
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

                if (checkAll) checkAll.checked = false;
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
            // Ganti event listener searchInput yang lama dengan ini
            document.getElementById('searchInput')?.addEventListener('input', function() {
                const q = this.value.toLowerCase();
                const filtered = allCategories.filter(item => item.name.toLowerCase().includes(q));
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
            // PAGINATION (NEXT & PREV)
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

            console.log("IDS:", selectedIds);

            if (selectedIds.some(id => !id)) {
                showError("ID tidak valid");
                return;
            }

            /* =========================
               FETCH DATA
            ========================== */
            function fetchData(page = 1) {

                fetch(`/api/admin/categories?page=${page}`, {
                        headers: {
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(res => {

                        const tbody = document.getElementById('tbody');
                        const catList = document.getElementById('catList');

                        const data = res.data ?? [];
                        const meta = res.meta ?? {};

                        // EMPTY STATE
                        if (data.length === 0) {
                            tbody.innerHTML = `
                <tr>
                    <td colspan="6" class="text-center text-muted">Data kosong</td>
                </tr>`;

                            if (catList) {
                                catList.innerHTML = `
                    <p class="text-center text-muted py-3" style="font-size:13px;">
                        Data kosong
                    </p>`;
                            }

                            return;
                        }

                        // SAVE STATE GLOBAL
                        allCategories = data;

                        window.currentPage = meta.current_page ?? page;
                        window.lastPage = meta.last_page ?? page;

                        // RENDER
                        renderTableBody(data);
                        renderCardList(data);

                        resetState();
                        updateEyeIcon();

                        // pagination sync (IMPORTANT)
                        renderPagination(meta);
                    })
                    .catch(err => console.error('Fetch error:', err));
            }

            // Dekstop Tabel Body
            function renderTableBody(data) {
                let html = '';

                data.forEach((item, index) => {
                    let image = item.image ?
                        (item.image.startsWith('http') ?
                            item.image :
                            `/storage/${item.image.replace(/^\/+/, '')}`) :
                        '/images/home/category/beras-putih.png';

                    let categoryClass = '';
                    if (item.name.toLowerCase() === 'premium') {
                        categoryClass = 'premium-category fw-normal';
                    } else if (item.name.toLowerCase() === 'medium') {
                        categoryClass = 'medium-category fw-normal';
                    } else if (item.name.toLowerCase() === 'ketan') {
                        categoryClass = 'ketan-category fw-normal';
                    } else {
                        categoryClass = getRandomBadgeClass(item.name);
                    }

                    let statusText = item.is_active ? 'Aktif' : 'Non-aktif';
                    let statusClass = item.is_active ? 'active-category fw-normal' :
                        'non-active-category fw-normal';

                    html += `
            <tr class="align-middle" data-id="${item.id}">
                <td class="text-center">
                    <input type="checkbox" class="custom-check row-check" value="${item.id}">
                </td>
                <td class="text-start ps-4">${index + 1}</td>
                <td class="text-start ps-4">
    <span class="badge ${categoryClass} category-badge">${item.name}</span>
</td>
                <td class="text-start ps-2">
                    <span class="badge ${statusClass}">${statusText}</span>
                </td>
              <td class="text-center">
            <img src="${item.image}" width="60" class="rounded">
        </td>
                <td class="text-center">
                    <div class="d-flex justify-content-center gap-2">
                        <a href="/admin/category/edit/${item.id}" class="btn-edit-admin text-decoration-none action-icon eye-action">
                            <span class="iconify" data-icon="flowbite:edit-outline" style="font-size:20px;"></span>
                        </a>
                        <a href="/admin/category/detail/${item.id}" class="btn-detail-admin text-decoration-none action-icon eye-action">
                            <span class="iconify" data-icon="heroicons-outline:eye" style="font-size:20px;"></span>
                        </a>
                    </div>
                </td>
            </tr>`;
                });

                tbody.innerHTML = html;
            }

            //Mobile Table Body
            function renderCardList(data) {
                const catList = document.getElementById('catList');
                if (!catList) return;

                if (data.length === 0) {
                    catList.innerHTML =
                        `<p class="text-center text-muted py-3" style="font-size:13px;">Tidak ada kategori ditemukan.</p>`;
                    return;
                }

                catList.innerHTML = data.map((item, index) => {
                    let image = item.image ?
                        (item.image.startsWith('http') ?
                            item.image :
                            `/storage/${item.image.replace(/^\/+/, '')}`) :
                        '/images/home/category/beras-putih.png';

                    let categoryClass = '';
                    if (item.name.toLowerCase() === 'premium') {
                        categoryClass = 'premium-category fw-normal';
                    } else if (item.name.toLowerCase() === 'medium') {
                        categoryClass = 'medium-category fw-normal';
                    } else if (item.name.toLowerCase() === 'ketan') {
                        categoryClass = 'ketan-category fw-normal';
                    } else {
                        categoryClass = getRandomBadgeClass(item.name);
                    }

                    let statusText = item.is_active ? 'Aktif' : 'Non-aktif';
                    let statusClass = item.is_active ? 'active-category fw-normal' :
                        'non-active-category fw-normal';

                    return `
        <div class="cat-card" data-id="${item.id}">
            <input type="checkbox" class="custom-check row-check" value="${item.id}" style="margin-top:4px; flex-shrink:0;">
            <img class="cat-img" src="${image}" alt="${item.name}">
            <div class="cat-info">
                <div class="cat-row1">
                    <span class="cat-name">${item.name}</span>
                    <span class="badge ${categoryClass} category-badge" style="font-size:11px;">${item.name}</span>
                </div>
                <div class="cat-row2">
                    <span class="badge ${statusClass}" style="font-size:11px;">${statusText}</span>
                    <div class="cat-actions">
                        <a href="/admin/category/edit/${item.id}" class="act-btn edit" title="Edit">
                            <span class="iconify" data-icon="flowbite:edit-outline" style="font-size:16px;"></span>
                        </a>
                        <a href="/admin/category/detail/${item.id}" class="act-btn detail" title="Detail">
                            <span class="iconify" data-icon="heroicons-outline:eye" style="font-size:16px;"></span>
                        </a>
                    </div>
                </div>
            </div>
        </div>`;
                }).join('');

                bindCardChecks();
            }

            // ── CHECKBOX MOBILE ──────────────────────────────────────────────────────────
            function bindCardChecks() {
                document.querySelectorAll('#catList .row-check').forEach(cb => {
                    cb.addEventListener('change', updateSelectLabel);
                });
            }

            function updateSelectLabel() {
                const all = document.querySelectorAll('#catList .row-check');
                const checked = document.querySelectorAll('#catList .row-check:checked');
                const label = document.getElementById('selectLabel');
                const checkAllMobile = document.getElementById('checkAllMobile');

                if (label) {
                    label.textContent = checked.length > 0 ?
                        `${checked.length} dari ${all.length} dipilih` :
                        'Pilih semua';
                }
                if (checkAllMobile) {
                    checkAllMobile.checked = all.length > 0 && checked.length === all.length;
                }
            }

            // Checkbox "pilih semua" mobile
            const checkAllMobile = document.getElementById('checkAllMobile');
            if (checkAllMobile) {
                checkAllMobile.addEventListener('change', function() {
                    document.querySelectorAll('#catList .row-check').forEach(cb => cb.checked = this
                        .checked);
                    updateSelectLabel();
                });
            }

            fetchData();

            /* =========================
               DELETE SELECT
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


            /* =========================
               CONFIRM DELETE
            ========================== */
            confirmBtn?.addEventListener("click", function() {

                fetch(`/api/admin/categories/bulk-delete`, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "Accept": "application/json",
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
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
                            showSuccess("Berhasil Menghapus Kategori");
                        }, 300);

                    })
                    .catch(err => console.error(err));
            });
            /* =========================
               RANDOM BADGE COLOR
            ========================== */
            function getRandomBadgeClass(name) {

                const colors = [
                    'bg-primary-subtle text-primary',
                    'bg-danger-subtle text-danger',
                    'bg-secondary-subtle text-secondary',
                    'bg-dark-subtle text-dark',
                    'bg-success-subtle text-success',
                    'bg-warning-subtle text-warning',
                    'bg-info-subtle text-info'
                ];

                let hash = 0;
                for (let i = 0; i < name.length; i++) {
                    hash = name.charCodeAt(i) + ((hash << 5) - hash);
                }

                return colors[Math.abs(hash) % colors.length] + ' fw-normal';
            }

        });
    </script>
@endsection
