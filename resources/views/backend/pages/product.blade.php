@extends('backend.app')

@section('title', 'Produk')

@section('content')

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
    </style>


    <!-- Top Action -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="position-relative" style="width:320px;">
            <h5 class="fw-semibold">Produk</h5>
        </div>

        <div class="d-flex gap-2">

            <!-- Kategori (1 Button) -->
            <div class="dropdown">
                <button id="kategoriDropdown" class="btn btn-sm btn-success d-flex align-items-center p-0" type="button"
                    data-bs-toggle="dropdown" aria-expanded="false">

                    <!-- Text -->
                    <span class="px-3 py-1">
                        Kategori
                    </span>

                    <!-- Icon Area -->
                    <span class="px-2 py-2 border-start border-white d-flex align-items-center">
                        <span id="dropdownIcon" class="iconify" data-icon="iconamoon:arrow-down-2-light"
                            style="font-size:14px;">
                        </span>
                    </span>

                </button>

                <ul class="dropdown-menu custom-dropdown">
                    <li><a class="dropdown-item premium" href="#">Premium</a></li>
                    <li><a class="dropdown-item medium" href="#">Medium</a></li>
                    <li><a class="dropdown-item ketan" href="#">Ketan</a></li>
                </ul>
            </div>

            <!-- Tambah Produk -->
            <button class="btn btn-success btn-sm px-3 d-flex align-items-center gap-2">
                Tambah Produk
                <i class="ri-add-line"></i>
            </button>

        </div>
    </div>
    <div class="card border-0 shadow-sm rounded-4 p-4">
        <div class="d-flex align-items-center mb-4 gap-4">

            <!-- Search -->
            <div class="position-relative search-width">
                <input type="text" class="form-control ps-4 pe-5" placeholder="Cari Produk disini">
                <i class="ri-search-line position-absolute top-50 end-0 translate-middle-y me-3 text-muted"></i>
            </div>

            <!-- Icon Trash -->
            <a href="#" class="trash-icon">
                <span class="iconify" data-icon="famicons:trash-outline"></span>
            </a>

        </div>
        <!-- Table -->
        <div class="table-responsive">
            <table class="table align-middle custom-table">
                <thead>
                    <tr class="text-muted small">

                        <th style="width:40px;"><input type="checkbox" id="checkAll" class="custom-check"></th>
                        <th>Produk</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @for ($i = 0; $i < 10; $i++)
                        <tr>
                            <td><input type="checkbox" class="custom-check row-check"></td>

                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <img src="{{ asset('images/home/category/beras-putih.png') }}" width="50"
                                        class="rounded">

                                    <div>
                                        <div class="fw-normal">
                                            15 Kg Beras Putih Premium Rojo Lele
                                        </div>

                                        @if ($i % 3 == 0)
                                            <span class="badge bg-success-subtle text-success fw-normal">Premium</span>
                                        @elseif($i % 3 == 1)
                                            <span class="badge bg-warning-subtle text-warning fw-normal">Medium</span>
                                        @else
                                            <span class="badge bg-info-subtle text-info fw-normal">Ketan</span>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <td class="fw-medium">Rp.100.000</td>
                            <td>125 Karung Tersedia</td>

                            <td class="text-center">
                                <a href="#" class="text-primary me-2 action-icon text-decoration-none">
                                    <span class="iconify" data-icon="flowbite:edit-outline" style="font-size:20px;"></span>
                                </a>

                                <a href="#" class="text-success action-icon text-decoration-none">
                                    <span class="iconify" data-icon="heroicons-outline:eye" style="font-size:20px;"></span>
                                </a>
                            </td>
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="custom-pagination">
            <a href="#" class="nav-text">&lt; Sebelumnya</a>

            <a href="#" class="active">1</a>
            <a href="#">2</a>
            <a href="#">3</a>
            <a href="#">4</a>
            <a href="#">5</a>

            <span class="dots">...</span>

            <a href="#">10</a>

            <a href="#" class="nav-text">Berikutnya &gt;</a>
        </div>

    </div>

    <script src="https://code.iconify.design/3/3.1.0/iconify.min.js"></script>
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
@endsection
