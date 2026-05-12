@extends('backend.app')

@section('title', 'Pesanan')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        .main {
            min-width: 0;
            /* penting untuk flex */
        }

        .content {
            overflow-x: auto;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .custom-table {
            min-width: 600px;
        }

        /* card */
        .card {
            border-radius: 5px;
            background: #fff;
            border: 1px solid #E8E8E9;
        }

        /* TABLE */
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

        thead tr {
            background: linear-gradient(90deg, #0D3523, #269B66);
        }

        thead th {
            background: transparent !important;
            color: white !important;
            border: none;
        }

        /* ACTION */
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

        /* MODAL */
        #deleteModal .modal-content {
            border: none;
            box-shadow: none;
        }

        #deleteModal .modal-header,
        #deleteModal .modal-footer {
            border: none;
        }
    </style>

    <!-- TOP -->
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <button class="btn btn-main btn-sm px-3 d-flex align-items-center gap-2">
            <span class="iconify" data-icon="uil:calendar" style="font-size:20px;"></span>
            <span id="currentMonth" class="fw-semibold"></span>
        </button>

    </div>

    <!-- CARD -->
    <div class="card shadow-sm p-3 p-md-4">

        <div class="d-flex align-items-center mb-4 gap-2">

            <!-- Search (FULL WIDTH) -->
            <div class="position-relative flex-grow-1">
                <input type="text" class="form-control ps-4 pe-5" placeholder="Cari Produk disini">
                <i class="ri-search-line position-absolute top-50 end-0 translate-middle-y me-3 text-muted"></i>
            </div>

            <!-- Button Filter -->
            <button class="btn btn-filter-admin d-flex align-items-center gap-1">
                <span class="iconify" data-icon="mingcute:filter-line"></span>
                Filter
            </button>

            <!-- Button Delete -->
            <div class="col-12 col-md-auto">
                <button class="btn btn-delete-admin w-100" id="deleteSelected">
                    <span class="iconify" data-icon="famicons:trash-outline"></span>
                </button>
            </div>
        </div>

        <!-- TABLE -->
        <div class="table-responsive">
            <table class="table align-middle custom-table table-hover ">
                <thead>
                    <tr class="text-center align-middle small">
                        <th style="width:40px;">
                            <input type="checkbox" id="checkAll" class="custom-check">
                        </th>
                        <th class="text-start">No</th>
                        <th class="text-start">Pesanan</th>
                        <th class="text-start">Tanggal</th>
                        <th class="text-start">Pelanggan</th>
                        <th class="text-center">Status Pesanan</th>
                        <th class="text-start">Total</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody>
    @forelse ($orders as $order)
        @php
            $badge = [
                'awaiting_payment' => 'badge-waiting-payment',
                'pending' => 'badge-confirmed',
                'cancelled' => 'badge-cancelled',
                'completed' => 'badge-completed',
            ][$order->status] ?? 'badge-confirmed';
        @endphp

        <tr class="text-center align-middle">
            <td>
                <input type="checkbox" class="custom-check row-check" value="{{ $order->id }}">
            </td>

            <td class="text-start">
                {{ $loop->iteration + ($orders->currentPage() - 1) * $orders->perPage() }}
            </td>

            <td class="text-start">
                {{ $order->order_number }}
            </td>

            <td class="text-start">
                {{ $order->created_at->format('d M Y, H.i') }}
            </td>

            <td class="text-start">
                {{ $order->user->name ?? '-' }}
            </td>

            <td class="text-center">
                <span class="badge {{ $badge }}">
                    {{ $order->status_label }}
                </span>
            </td>

            <td class="text-start">
                {{ $order->total_format }}
            </td>

            <td class="text-center">
                <a href=""
                   class="btn-edit-admin text-decoration-none action-icon eye-action">
                    <span class="iconify" data-icon="flowbite:edit-outline" style="font-size:20px;"></span>
                </a>

                <a href="{{ route('admin.order.detail', $order->id) }}"
                   class="btn-detail-admin text-decoration-none action-icon eye-action">
                    <span class="iconify" data-icon="heroicons-outline:eye" style="font-size:20px;"></span>
                </a>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="8" class="text-center py-4">
                Data pesanan belum tersedia.
            </td>
        </tr>
    @endforelse
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

    <!-- MODAL DELETE -->
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

                <p class="mb-0 fw-medium">Berhasil Menghapus Pesanan</p>

            </div>
        </div>
    </div>

    {{-- Modal Berhasil Update --}}
    <div class="modal fade" id="successUpdateModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content p-5 text-center">

                <!-- ICON SUCCESS -->
                <div class="d-flex justify-content-center mb-3">
                    <div class="d-flex align-items-center justify-content-center rounded-circle"
                        style="width:50px; height:50px; background:#22C55E;">

                        <i class="iconify text-white fs-1" data-icon="iconamoon:check-bold"></i>

                    </div>
                </div>

                <p class="mb-0 fw-medium">Berhasil Mengubah Pesanan</p>

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
@endsection
