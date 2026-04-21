@extends('backend.app')

@section('title', 'Kategori')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .custom-table thead th {
            font-size: 14px;
            font-weight: 500;
            color: #6c757d;
        }

        .custom-table tbody td {
            font-size: 14px;
            vertical-align: middle;
        }

        .custom-table tbody tr {
            transition: 0.2s;
        }

        .custom-table tbody tr:hover {
            background: #f8f9fa;
        }

        .badge {
            font-size: 12px;
            padding: 6px 10px;
            border-radius: 8px;
            font-weight: 400;
        }

        .action-icon {
            font-size: 20px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            transition: 0.2s ease;
        }

        .action-icon:hover {
            transform: scale(1.15);
        }

        /* Paginasi */
        .custom-pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 12px;
            margin-top: 20px;
        }

        .custom-pagination a {
            text-decoration: none;
            color: #666;
            font-weight: 500;
            padding: 2px 10px;
            border-radius: 6px;
            transition: all 0.2s ease;
        }

        /* Hover effect */
        .custom-pagination a:hover {
            color: #0d6efd;
        }

        /* Active jadi seperti button */
        .custom-pagination a.active {
            background-color: #eff8ff;
            color: #60b5ff;
            font-weight: 600;
        }

        /* Supaya hover tidak ganggu active */
        .custom-pagination a.active:hover {
            background-color: #0b5ed7;
            color: #fff;
        }

        .custom-pagination .dots {
            color: #aaa;
        }

        /* Search */
        .trash-icon {
            font-size: 22px;
            color: #dc3545;
            transition: 0.2s ease;
            text-decoration: none;
        }

        .trash-icon:hover {
            color: #bb2d3b;
            transform: scale(1.1);
        }

        .dropdown .btn span.border-start {
            border-left: 2px solid #fff !important;
        }

        /* Default item (tidak hover & tidak aktif) */
        .custom-dropdown,
        .custom-dropdown .dropdown-item {
            background: #E8E8E9;
            color: #656769;
        }

        /* PREMIUM HOVER */
        .custom-dropdown .premium:hover {
            background: var(--bs-success-bg-subtle);
            color: var(--bs-success);
        }

        /* MEDIUM HOVER */
        .custom-dropdown .medium:hover {
            background: var(--bs-warning-bg-subtle);
            color: var(--bs-warning);
        }

        /* KETAN HOVER */
        .custom-dropdown .ketan:hover {
            background: var(--bs-info-bg-subtle);
            color: var(--bs-info);
        }

        /* Checkbox */
        .custom-check {
            appearance: none;
            width: 15px;
            height: 15px;
            border: 2px solid #ccc;
            border-radius: 4px;
            cursor: pointer;
            position: relative;
            transition: 0.2s ease;
        }

        /* Saat dicentang */
        .custom-check:checked {
            background-color: #60B5FF;
            border-color: #60B5FF;
        }

        /* Icon basil:check-solid */
        .custom-check:checked::after {
            content: "";
            position: absolute;
            inset: 0;
            background-image: url("https://api.iconify.design/basil/check-solid.svg?color=white");
            background-repeat: no-repeat;
            background-position: center;
            background-size: 14px;
        }

        thead tr {
            background: linear-gradient(90deg, #0D3523, #269B66);
        }

        thead th {
            background: transparent !important;
            color: white !important;
            border: none;
        }
    </style>


    <!-- Top Action -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <button class="btn btn-custom-green btn-sm px-3 d-flex align-items-center gap-2">
            <span class="iconify" data-icon="uil:calendar" style="font-size:20px;"></span>
            <span id="currentMonth" class="fw-semibold"></span>
        </button>
        <div class="d-flex gap-2">

            <!-- Kategori (1 Button) -->
            <div class="dropdown">
                <button id="kategoriDropdown" class="btn btn-sm btn-custom-green d-flex align-items-center p-0"
                    type="button" data-bs-toggle="dropdown" aria-expanded="false">


            </div>

            <!-- Tambah Kategori -->
            <a href="{{ route('admin.category.create') }}"
                class="btn btn-custom-green btn-sm px-3 d-flex align-items-center gap-2">
                Tambah Kategori
                <i class="ri-add-line"></i>
            </a>

        </div>
    </div>
    <div class="card border-0 shadow-sm rounded-4 p-4">
        <div class="d-flex align-items-center mb-4 gap-2">

            <!-- Search (FULL WIDTH) -->
            <div class="position-relative flex-grow-1">
                <input type="text" id="searchInput" class="form-control ps-4 pe-5" placeholder="Cari Kategori disini">
                <i class="ri-search-line position-absolute top-50 end-0 translate-middle-y me-3 text-muted"></i>
            </div>

            <!-- Button Delete -->
            <button class="btn btn-outline-secondary delete-category" id="deleteSelected">
                <span class="iconify" data-icon="famicons:trash-outline"></span>
            </button>

        </div>
        <!-- Table -->
        <div class="table-responsive">
            <table class="table align-middle custom-table">
                <thead>
                    <tr class="small text-center align-middle">
                        <th style="width:40px;">
                            <input type="checkbox" id="checkAll" class="custom-check">
                        </th>
                        <th class="text-start">Nomor</th>
                        <th>Nama Kategori</th>
                        <th>Gambar Kategori</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody id="categoryTableBody">
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="custom-pagination">
            <a href="#" class="active">1</a>
        </div>

    </div>

    {{-- MODAL DELETE --}}
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title text-danger">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <p id="deleteMessage">
                        Apakah anda yakin ingin menghapus kategori ini?
                    </p>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Batal
                    </button>

                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                        Hapus
                    </button>
                </div>

            </div>
        </div>
    </div>

    <script src="https://code.iconify.design/3/3.1.0/iconify.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const monthEl = document.getElementById('currentMonth');

            const now = new Date();
            const options = {
                month: 'long',
                year: 'numeric'
            };

            monthEl.textContent = now.toLocaleDateString('id-ID', options);
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            /* =========================
               DROPDOWN ICON TOGGLE
            ========================== */
            const dropdown = document.getElementById('kategoriDropdown');
            const icon = document.getElementById('dropdownIcon');

            if (dropdown && icon) {
                dropdown.addEventListener('show.bs.dropdown', function() {
                    icon.setAttribute('data-icon', 'iconamoon:arrow-up-2-light');
                });

                dropdown.addEventListener('hide.bs.dropdown', function() {
                    icon.setAttribute('data-icon', 'iconamoon:arrow-down-2-light');
                });
            }

            /* =========================
               CHECK ALL FUNCTION
            ========================== */
            const checkAll = document.getElementById('checkAll');

            if (checkAll) {
                checkAll.addEventListener('change', function() {
                    const rows = document.querySelectorAll('.row-check');
                    rows.forEach(cb => cb.checked = this.checked);
                });
            }

        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            const searchInput = document.getElementById("searchInput");
            const tbody = document.getElementById('categoryTableBody');
            const checkAll = document.getElementById("checkAll");

            // =========================
            // SEARCH
            // =========================
            searchInput.addEventListener("keyup", function() {

                const keyword = this.value.toLowerCase();
                const rows = tbody.querySelectorAll("tr");

                rows.forEach(row => {
                    row.style.display = row.innerText.toLowerCase().includes(keyword) ?
                        "" :
                        "none";
                });

            });

            // =========================
            // TOGGLE EYE ICON
            // =========================
            function updateEyeIcon() {

                const checkedCount = tbody.querySelectorAll(".row-check:checked").length;
                const eyeIcons = document.querySelectorAll(".eye-action");

                eyeIcons.forEach(el => {
                    el.style.display = checkedCount > 1 ? "none" : "";
                });
            }

            // =========================
            // EVENT DELEGATION (FIX IMPORTANT)
            // =========================
            tbody.addEventListener("change", function(e) {
                if (e.target.classList.contains("row-check")) {
                    updateEyeIcon();
                }
            });

            // checkAll
            if (checkAll) {
                checkAll.addEventListener("change", function() {
                    document.querySelectorAll(".row-check")
                        .forEach(cb => cb.checked = this.checked);

                    updateEyeIcon();
                });
            }

            // =========================
            // FETCH DATA
            // =========================
            fetch('/api/admin/categories', {
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(res => {

                    tbody.innerHTML = '';

                    if (!res.data || res.data.length === 0) {
                        tbody.innerHTML = `
                <tr>
                    <td colspan="5" class="text-center text-muted">Data kosong</td>
                </tr>
            `;
                        return;
                    }

                    let html = '';

                    res.data.forEach((item, index) => {

                        let image = item.image ?
                            `/storage/${item.image}` :
                            `/images/home/category/beras-putih.png`;

                        let categoryClass = '';

                        if (item.name.toLowerCase() === 'premium') {
                            categoryClass = 'bg-success-subtle text-success fw-normal';
                        } else if (item.name.toLowerCase() === 'medium') {
                            categoryClass = 'bg-warning-subtle text-warning fw-normal';
                        } else {
                            categoryClass = 'bg-info-subtle text-info fw-normal';
                        }

                        html += `
                <tr class="align-middle">

                    <td class="text-center">
                        <input type="checkbox" class="custom-check row-check" value="${item.id}">
                    </td>

                    <td class="text-start ps-4">${index + 1}</td>

                    <td class="text-center">
                        <span class="badge ${categoryClass} category-badge">${item.name}</span>
                    </td>

                    <td class="text-center">
                        <img src="${image}" width="60" class="rounded">
                    </td>

                    <td class="text-center">
                      <div class="d-flex justify-content-center align-items-center gap-2">

                       <a href="/admin/category/edit/${item.id}" class="text-primary text-decoration-none action-icon eye-action">
    <span class="iconify" data-icon="flowbite:edit-outline" style="font-size:20px;"></span>
</a>

                        <a href="#" class="text-success action-icon eye-action">
                            <span class="iconify" data-icon="heroicons-outline:eye" style="font-size:20px;"></span>
                        </a>

                    </div>
                    </td>

                </tr>
            `;
                    });

                    tbody.innerHTML = html;

                })
                .catch(err => console.error('Fetch error:', err));

        });
    </script>

    {{-- Fetch Delete --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            const checkAll = document.getElementById("checkAll");
            const deleteBtn = document.getElementById("deleteSelected");
            const confirmBtn = document.getElementById("confirmDeleteBtn");
            const deleteMessage = document.getElementById("deleteMessage");

            let selectedIds = [];

            // =========================
            // CHECK ALL
            // =========================
            if (checkAll) {
                checkAll.addEventListener("change", function() {
                    document.querySelectorAll(".row-check").forEach(cb => {
                        cb.checked = this.checked;
                    });
                });
            }

            // =========================
            // OPEN MODAL
            // =========================
            deleteBtn?.addEventListener("click", function() {

                selectedIds = Array.from(document.querySelectorAll(".row-check:checked"))
                    .map(cb => cb.value);

                if (selectedIds.length === 0) {
                    alert("Pilih data terlebih dahulu");
                    return;
                }

                deleteMessage.innerHTML = `
            Apakah anda yakin ingin menghapus
            <b>${selectedIds.length}</b> kategori ini?
        `;

                const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
                modal.show();
            });

            // =========================
            // CONFIRM DELETE
            // =========================
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
                    .then(res => res.json())
                    .then(data => {

                        if (data.success) {

                            // hapus row dari table
                            selectedIds.forEach(id => {
                                document.querySelector(`.row-check[value="${id}"]`)
                                    ?.closest("tr")
                                    ?.remove();
                            });

                            // reset checkbox
                            if (checkAll) checkAll.checked = false;

                            // tutup modal
                            const modalEl = document.getElementById('deleteModal');
                            const modal = bootstrap.Modal.getInstance(modalEl);
                            modal?.hide();

                            // OPTIONAL: silent success (tanpa popup)
                            console.log("Kategori berhasil dihapus");

                        } else {
                            alert(data.message || "Gagal menghapus data");
                        }

                    })
                    .catch(err => {
                        console.error(err);
                        alert("Terjadi kesalahan server");
                    });

            });

        });
    </script>
@endsection
