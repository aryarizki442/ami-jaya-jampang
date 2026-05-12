@extends('backend.app')

@section('title', 'Pembayaran')

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
    .custom-table thead tr {
        background: linear-gradient(90deg, #0D3523, #269B66);
    }

    .custom-table thead th {
        color: #fff !important;
        border: none;
        font-size: 14px;
        font-weight: 500;
        padding: 14px;
    }

    .custom-table tbody td {
        font-size: 14px;
        vertical-align: middle;
        padding: 15px 14px;
    }

    .custom-check {
        appearance: none;
        width: 15px;
        height: 15px;
        border: 1px solid #ddd;
        border-radius: 4px;
        cursor: pointer;
    }

    .custom-check:checked {
        background-color: #60B5FF;
        border-color: #60B5FF;
    }

    .payment-badge {
        display: inline-block;
        background: #F3F4F6;
        color: #6B7280;
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 500;
    }

    .action-icon {
        font-size: 20px;
        color: #269B66;
        transition: .2s;
    }

    .action-icon:hover {
        transform: scale(1.15);
    }

    .custom-pagination {
        display: flex;
        justify-content: center;
        gap: 8px;
        margin-top: 20px;
        flex-wrap: wrap;
    }

    .custom-pagination a {
        text-decoration: none;
        color: #666;
        padding: 5px 11px;
        border-radius: 7px;
    }

    .custom-pagination a.active {
        background-color: #eff8ff;
        color: #60b5ff;
        font-weight: 600;
    }
</style>

<div class="d-flex flex-wrap align-items-center gap-2 mb-3">
    <button class="btn btn-main btn-sm px-3 d-flex align-items-center gap-2">
        <span class="iconify" data-icon="uil:calendar" style="font-size:18px;"></span>
        <span>{{ now()->translatedFormat('d F Y') }}</span>
    </button>
</div>

<div class="card border-0 shadow-sm rounded-4 p-4">

    <div class="row g-2 align-items-center mb-3">

        <div class="col-12 col-md">
            <div class="position-relative">
                <input type="text" id="searchInput" class="form-control pe-5" placeholder="Cari Pesanan disini">
                <i class="ri-search-line position-absolute top-50 end-0 translate-middle-y me-3 text-muted"></i>
            </div>
        </div>

        <div class="col-12 col-md-auto">
            <select id="statusFilter" class="form-select">
                <option value="">Semua Status</option>
                <option value="pending">Menunggu Pembayaran</option>
                <option value="paid">Dibayar</option>
                <option value="failed">Gagal</option>
                <option value="expired">Expired</option>
                <option value="refunded">Refund</option>
            </select>
        </div>

    </div>

    <div class="table-responsive">
        <table class="table align-middle custom-table table-hover">
            <thead>
                <tr>
                    <th style="width:40px;">
                        <input type="checkbox" id="checkAll" class="custom-check">
                    </th>
                    <th>Pesanan</th>
                    <th>Tanggal</th>
                    <th>Pelanggan</th>
                    <th class="text-center">Status Pembayaran</th>
                    <th class="text-center">Metode Pembayaran</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>

            <tbody id="PaymentTableBody">
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">Memuat data...</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="custom-pagination"></div>

</div>

<script src="https://code.iconify.design/3/3.1.0/iconify.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const tbody = document.getElementById('PaymentTableBody');
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const checkAll = document.getElementById('checkAll');

    let filters = {
        page: 1,
        search: '',
        status: ''
    };

    window.currentPage = 1;
    window.lastPage = 1;

    function fetchData(page = 1) {
        filters.page = page;

        let url = `/api/admin/payments?page=${filters.page}`;

        if (filters.search) {
            url += `&search=${encodeURIComponent(filters.search)}`;
        }

        if (filters.status) {
            url += `&status=${encodeURIComponent(filters.status)}`;
        }

        fetch(url, {
            headers: {
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(res => {
            const pagination = res.data;

            if (!pagination || !Array.isArray(pagination.data) || pagination.data.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            Data pembayaran kosong
                        </td>
                    </tr>
                `;

                document.querySelector('.custom-pagination').innerHTML = '';
                return;
            }

            renderTableBody(pagination.data);
            renderPagination(pagination);

            window.currentPage = pagination.current_page;
            window.lastPage = pagination.last_page;
        })
        .catch(() => {
            tbody.innerHTML = `
                <tr>
                    <td colspan="7" class="text-center text-danger py-4">
                        Gagal memuat data pembayaran
                    </td>
                </tr>
            `;
        });
    }

    function renderTableBody(data) {
        let html = '';

        data.forEach(item => {
            html += `
                <tr data-id="${item.id}">
                    <td>
                        <input type="checkbox" class="custom-check row-check" value="${item.id}">
                    </td>

                    <td class="fw-semibold">
                        #${item.order_number ?? '-'}
                    </td>

                    <td>
                        ${item.date ?? '-'}
                    </td>

                    <td>
                        ${item.customer ?? '-'}
                    </td>

                    <td class="text-center">
                        <span class="payment-badge">
                            ${item.payment_status_label ?? '-'}
                        </span>
                    </td>

                    <td class="text-center">
                        ${item.payment_method ?? '-'}
                    </td>

                    <td class="text-center">
                        <a href="/admin/pembayaran/detail/${item.id}"
                           class="action-icon text-decoration-none">
                            <span class="iconify" data-icon="heroicons-outline:eye"></span>
                        </a>
                    </td>
                </tr>
            `;
        });

        tbody.innerHTML = html;
    }

    function renderPagination(pagination) {
        let html = '';

        html += `<a href="#" data-page="prev">Sebelumnya</a>`;

        for (let i = 1; i <= pagination.last_page; i++) {
            html += `
                <a href="#"
                   data-page="${i}"
                   class="${i === pagination.current_page ? 'active' : ''}">
                    ${i}
                </a>
            `;
        }

        html += `<a href="#" data-page="next">Berikutnya</a>`;

        document.querySelector('.custom-pagination').innerHTML = html;
    }

    document.addEventListener('click', function (e) {
        const el = e.target.closest('.custom-pagination a');
        if (!el) return;

        e.preventDefault();

        const page = el.dataset.page;

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

    searchInput.addEventListener('input', function () {
        filters.search = this.value;
        fetchData(1);
    });

    statusFilter.addEventListener('change', function () {
        filters.status = this.value;
        fetchData(1);
    });

    checkAll.addEventListener('change', function () {
        document.querySelectorAll('.row-check').forEach(cb => {
            cb.checked = this.checked;
        });
    });

    fetchData();
});
</script>

@endsection