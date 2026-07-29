@extends('backend.app')

@section('title', 'Dashboard')

@section('page-title', 'Dashboard')

@section('content')
    <style>
        /* GLOBAL FONT SIZE (lebih kecil & rapi) */
        body {
            font-size: 13px;
        }

        h4 {
            font-size: 18px;
            margin: 0;
        }

        h6 {
            font-size: 13px;
            margin-bottom: 5px;
        }

        .card-header span {
            font-size: 13px;
            font-weight: 600;
        }

        .card-header small {
            font-size: 11px;
        }

        .table th,
        .table td {
            font-size: 12px;
            padding: 8px;
        }

        .badge {
            font-size: 11px;
        }

        /* EQUAL HEIGHT */
        .row-equal {
            display: flex;
            flex-wrap: wrap;
        }

        .row-equal>[class*='col-'] {
            display: flex;
        }

        /* CARD GRADIENT */
        .card-custom {
            width: 100%;
            display: flex;
            flex-direction: column;
            background: linear-gradient(90deg, #0D3523, #1F7D53);
            border-radius: 10px;
            overflow: hidden;
        }

        .card-custom .card-body {
            flex: 1;
        }

        /* TABLE FULL HEIGHT */
        .table {
            height: 100%;
        }

        /* .table thead th {
                                                                                                                                                                                                                                                                                                                                                                                                    color: #D1D3D8 !important;
                                                                                                                                                                                                                                                                                                                                                                                                    font-weight: 500;
                                                                                                                                                                                                                                                                                                                                                                                                } */
    </style>

    <!-- CARD ATAS -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6>Total Pesanan</h6>
                    <h4 id="totalSold">0 Karung</h4>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6>Pendapatan Hari ini</h6>
                    <h4 id="totalRevenue">Rp. 0</h4>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6>Total Produk</h6>
                    <h4 id="totalProducts">0 Item</h4>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6>Stok Hampir Habis</h6>
                    <h4 id="lowStock">0 Item</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- ROW 1 -->
    <div class="row row-equal">
        <div class="col-md-8">
            <div class="card card-custom shadow-sm border-0">
                <div class="card-header d-flex justify-content-between">
                    <span class="fw-semibold text-white">Grafik Penjualan Bulanan</span>
                    <div class="d-flex align-items-center gap-2">
                        <span id="currentYear" class="fw-semibold text-white">2026</span>

                        <button id="openCalendar"
                            class="btn btn-sm border-0 bg-transparent d-flex align-items-center gap-2">
                            <span class="iconify" data-icon="uis:calendar" style="font-size:16px; color:#fff;">
                            </span>
                        </button>

                        @include('backend.components.chart-calendar')
                    </div>
                </div>
                <div class="card-body p-3" style="background:#fff;">
                    <div style="height:300px;">
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-custom shadow-sm border-0">
                <div class="card-header d-flex justify-content-between">
                    <span class="fw-semibold text-white">Banyak Terjual</span>
                    <a href="{{ url('/admin/report') }}" class="text-white text-decoration-none small">
                        Lihat Semua
                    </a>
                </div>

                <div class="card-body p-0">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th class="text-start">Nama Produk</th>
                                <th class="text-center">Terjual</th>
                                <th class="text-center">Kategori</th>
                            </tr>
                        </thead>

                        <tbody id="topProductsBody">
                            <tr>
                                <td colspan="3" class="text-center text-muted py-3">
                                    Memuat data...
                                </td>
                            </tr>
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- ROW 2 -->
    <div class="row mt-4 row-equal">
        <div class="col-md-8">

            <div class="card card-custom shadow-sm border-0">

                <div class="card-header d-flex justify-content-between">

                    <span class="fw-semibold text-white">
                        Pesanan Terbaru
                    </span>

                    <a href="{{ url('/admin/payment') }}" class="text-white text-decoration-none small">

                        Lihat Semua

                    </a>

                </div>

                <div class="card-body p-0 text-center">

                    <table class="table mb-0">

                        <thead>

                            <tr>

                                <th>ID Pesanan</th>

                                <th>Tanggal</th>

                                <th>Pelanggan</th>

                                <th>Total</th>

                                <th>Status</th>

                            </tr>

                        </thead>

                        <tbody id="latestOrdersBody">

                            <tr>

                                <td colspan="5" class="text-center text-muted py-3">

                                    Memuat data...

                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

        <div class="col-md-4">
            <div class="card card-custom shadow-sm border-0">
                <div class="card-header d-flex justify-content-between">
                    <span class="fw-semibold text-white">Stok Hampir Habis</span>

                    <a href="{{ url('/admin/report') }}" class="text-white text-decoration-none small">
                        Lihat Semua
                    </a>
                </div>

                <div class="card-body p-0">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th class="text-center">Sisa Stok</th>
                            </tr>
                        </thead>

                        <tbody id="lowStockBody">
                            <tr>
                                <td colspan="2" class="text-center text-muted py-3">
                                    Memuat data...
                                </td>
                            </tr>
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.iconify.design/3/3.1.1/iconify.min.js"></script>
    <script>
        const DashboardAPI = {

            async getSummary() {
                const res = await fetch('/api/admin/reports/summary');

                if (!res.ok) throw new Error("HTTP " + res.status);

                return await res.json();
            },

            async getReports(
                month = null,
                year = null,
                startDate = null,
                endDate = null
            ) {

                const params =
                    new URLSearchParams();

                params.append(
                    'page',
                    '1'
                );

                params.append(
                    'per_page',
                    '1000'
                );

                if (month) {

                    params.append(
                        'month',
                        month
                    );

                }

                if (year) {

                    params.append(
                        'year',
                        year
                    );

                }

                if (startDate) {

                    params.append(
                        'start_date',
                        startDate
                    );

                }

                if (endDate) {

                    params.append(
                        'end_date',
                        endDate
                    );

                }

                const res =
                    await fetch(
                        `/api/admin/reports?${params.toString()}`
                    );

                if (!res.ok) {

                    throw new Error(
                        "HTTP " + res.status
                    );

                }

                return await res.json();

            },
            async getLowStock() {

                const res = await fetch('/api/admin/reports/low-stock');

                if (!res.ok) throw new Error("HTTP " + res.status);

                return await res.json();
            },
            async getLatestOrders() {

                const res = await fetch(
                    '/api/admin/payments?page=1&per_page=5'
                );

                if (!res.ok) {
                    throw new Error(
                        "HTTP " + res.status
                    );
                }

                return await res.json();
            },
            async getChart(type, year = null, month = null, startDate = null, endDate = null) {

                const params = new URLSearchParams();

                params.append("type", type);

                if (year) params.append("year", year);
                if (month) params.append("month", month);
                if (startDate) params.append("start_date", startDate);
                if (endDate) params.append("end_date", endDate);

                const res = await fetch(`/api/admin/dashboard/chart?${params.toString()}`);

                if (!res.ok) throw new Error("HTTP " + res.status);

                return await res.json();
            },
        };

        async function loadTopProducts(month, year) {

            try {

                const response = await DashboardAPI.getReports(month, year);

                const tbody = document.getElementById('topProductsBody');

                tbody.innerHTML = '';

                const items = response.data.items ?? [];

                if (items.length === 0) {

                    tbody.innerHTML = `
                    <tr>
                        <td colspan="3" class="text-center text-muted py-3">
                            Tidak ada data
                        </td>
                    </tr>
                `;

                    return;
                }

                items.forEach(item => {

                    let badge = 'bg-secondary-subtle text-secondary';

                    switch (item.category) {
                        case 'Premium':
                            badge = 'bg-success-subtle text-success';
                            break;

                        case 'Medium':
                            badge = 'bg-warning-subtle text-warning';
                            break;

                        case 'Ketan':
                            badge = 'bg-info-subtle text-info';
                            break;
                    }

                    tbody.innerHTML += `
                    <tr>
                        <td class="text-start">${item.product_name}</td>
                        <td class="text-center">${item.total_sold}</td>
                        <td class="text-center">
                            <span class="badge ${badge}">
                                ${item.category}
                            </span>
                        </td>
                    </tr>
                `;
                });

            } catch (err) {
                console.error(err);

                document.getElementById('topProductsBody').innerHTML = `
                <tr>
                    <td colspan="3" class="text-center text-danger py-3">
                        Gagal memuat data
                    </td>
                </tr>
            `;
            }
        }

        async function loadLowStock() {

            try {

                const response = await DashboardAPI.getLowStock();

                const tbody = document.getElementById('lowStockBody');

                tbody.innerHTML = '';

                const items = response.data ?? [];

                if (!items.length) {

                    tbody.innerHTML = `
                <tr>
                    <td colspan="2" class="text-center text-muted py-3">
                        Tidak ada produk
                    </td>
                </tr>
            `;

                    return;
                }

                items.forEach(item => {

                    tbody.innerHTML += `
                <tr>
                    <td>${item.name}</td>
                    <td class="text-center">
                        <span class="badge text-dark fw-normal">
                            ${item.stock}
                        </span>
                    </td>
                </tr>
            `;

                });

            } catch (err) {

                console.error(err);

                document.getElementById('lowStockBody').innerHTML = `
            <tr>
                <td colspan="2" class="text-center text-danger">
                    Gagal memuat data
                </td>
            </tr>
        `;
            }

        }

        /* =========================
           GRAFIK PENJUALAN BULANAN
        ========================= */
        let salesChart = null;

        async function loadSalesChart(type, options = {}) {

            try {

                const response = await DashboardAPI.getChart(
                    type,
                    options.year,
                    options.month,
                    options.startDate,
                    options.endDate
                );

                if (salesChart) {
                    salesChart.destroy();
                }

                const maxValue = Math.max(...response.data);

                salesChart = new Chart(
                    document.getElementById("salesChart"), {
                        type: "bar",

                        data: {
                            labels: response.labels,
                            datasets: [{
                                label: "Penjualan (Karung)",
                                data: response.data,
                                backgroundColor: "#1F6B50",
                                borderColor: "#1F6B50",
                                borderWidth: 1,
                                barPercentage: .45,
                                categoryPercentage: .8
                            }]
                        },

                        options: {
                            responsive: true,
                            maintainAspectRatio: false,

                            plugins: {
                                legend: {
                                    position: "bottom",
                                    align: "start"
                                },

                            },

                            scales: {
                                y: {
                                    beginAtZero: true,
                                    suggestedMax: Math.ceil(maxValue / 10) * 10,

                                    ticks: {
                                        stepSize: 10,
                                    },

                                },

                                x: {
                                    title: {
                                        display: true,
                                        text: "Periode"
                                    }
                                }
                            }
                        }
                    }
                );

            } catch (e) {

                console.error(e);

            }

        }
        /* =========================
           FILTER TAHUN GRAFIK
        ========================= */

        function changeChartYear(selectedYear) {

            const year = Number(selectedYear);

            if (!year || year < 2000) {
                return;
            }

            // Ubah tulisan tahun di header
            document.getElementById(
                'currentYear'
            ).textContent = year;

            // Ambil ulang data grafik
            loadSalesChart("year", {
                year: year
            });

        }

        /* =========================
           PESANAN TERBARU
        ========================= */

        function formatDashboardDate(dateString) {

            if (!dateString) {
                return '-';
            }

            const date =
                new Date(dateString);

            return date.toLocaleDateString(
                'id-ID', {

                    day: '2-digit',

                    month: '2-digit',

                    year: 'numeric'

                }
            );

        }

        function getPaymentStatus(status) {

            const statuses = {

                pending: {

                    label: 'Menunggu Pembayaran',

                    className: 'bg-secondary-subtle text-secondary'

                },


                paid: {

                    label: 'Dibayar',

                    className: 'bg-success-subtle text-success'

                },


                failed: {

                    label: 'Pembayaran Gagal',

                    className: 'bg-danger-subtle text-danger'

                },


                expired: {

                    label: 'Kedaluwarsa',

                    className: 'bg-warning-subtle text-warning'

                },


                refunded: {

                    label: 'Dana Dikembalikan',

                    className: 'bg-info-subtle text-info'

                },


                partially_refunded: {

                    label: 'Refund Sebagian',

                    className: 'bg-info-subtle text-info'

                }

            };


            return statuses[status] ?? {

                label: '-',

                className: 'bg-secondary-subtle text-secondary'

            };

        }

        async function loadLatestOrders() {

            const tbody =
                document.getElementById(
                    'latestOrdersBody'
                );

            if (!tbody) return;

            try {

                const response =
                    await DashboardAPI
                    .getLatestOrders();

                /*
                Struktur API pembayaran:

                response.data.data
                */

                const orders = (
                    response.data?.data ?? []
                ).slice(0, 5);

                if (!orders.length) {

                    tbody.innerHTML = `
                <tr>

                    <td colspan="5"
                        class="text-center text-muted py-3">

                        Belum ada pesanan

                    </td>

                </tr>
            `;

                    return;
                }

                tbody.innerHTML = '';

                orders.forEach(order => {

                    const payment =
                        order.payment ?? {};

                    const status =
                        payment.status ?? 'pending';

                    const statusData =
                        getPaymentStatus(status);

                    tbody.innerHTML += `

                <tr>

                    <td>

                        ${order.order_number ?? '-'}

                    </td>


                    <td>

                        ${formatDashboardDate(
                            order.created_at
                        )}

                    </td>


                    <td>

                        ${order.customer_name ?? '-'}

                    </td>

                    <td>

                        ${order.amount_format ??
                            rupiah(order.amount ?? 0)}

                    </td>


                    <td>

                        <span
                            class="badge
                            ${statusData.className}
                            fw-normal">

                            ${statusData.label}

                        </span>

                    </td>

                </tr>

            `;
                });

            } catch (error) {

                console.error(
                    'Gagal mengambil pesanan:',
                    error
                );

                tbody.innerHTML = `

            <tr>

                <td colspan="5"
                    class="text-center text-danger py-3">

                    Gagal memuat pesanan

                </td>

            </tr>

        `;
            }

        }

        function rupiah(nominal) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(nominal);
        }
        /* =========================
           FILTER GRAFIK DARI KALENDER
        ========================= */

        document.addEventListener("dateRangeSelected", async function(event) {

            const {
                type,
                year,
                month,
                start,
                end
            } = event.detail;

            document.getElementById("currentYear").textContent = year;

            await loadSalesChart(type, {
                year: year,
                month: month,
                startDate: start,
                endDate: end
            });

        });
        document.addEventListener("DOMContentLoaded", async () => {

            const month =
                new Date().getMonth() + 1;

            const year =
                new Date().getFullYear();

            // Tampilkan tahun sekarang
            document.getElementById(
                'currentYear'
            ).textContent = year;

            // LOAD DATA DASHBOARD
            await loadTopProducts(
                month,
                year
            );

            await loadLatestOrders();

            await loadLowStock();

            // LOAD GRAFIK PENJUALAN
            await loadSalesChart("year", {
                year: year
            });

            try {

                const response = await DashboardAPI.getSummary();

                const current = response.data.current;

                document.getElementById('totalSold').textContent =
                    `${current.total_sold} Karung`;

                document.getElementById('totalRevenue').textContent =
                    rupiah(current.total_revenue);

                document.getElementById('totalProducts').textContent =
                    `${current.total_products} Item`;

                document.getElementById('lowStock').textContent =
                    `${current.low_stock} Item`;

            } catch (err) {
                console.error(err);
            }

        });
    </script>
@endsection
