<style>
    /* Tambahan CSS untuk Transaction Detail Modal */
    #transactionDetailModal .modal-body hr {
        margin: 1rem 0;
        opacity: 0.5;
    }

    #transactionDetailModal #trxProductItems {
        max-height: 400px;
        overflow-y: auto;
        padding-right: 5px;
    }

    #transactionDetailModal #trxProductItems::-webkit-scrollbar {
        width: 4px;
    }

    #transactionDetailModal #trxProductItems::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    #transactionDetailModal #trxProductItems::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 10px;
    }

    /* Button action container */
    #transactionDetailModal .modal-footer {
        border-top: none;
        padding-top: 0;
    }

    /* Responsive untuk modal di mobile */
    @media (max-width: 576px) {
        #transactionDetailModal .modal-body {
            padding: 0 1rem 1rem !important;
        }

        #transactionDetailModal .d-flex.justify-content-between {
            flex-wrap: wrap;
            gap: 8px;
        }

        #transactionDetailModal #trxProductItems .d-flex {
            flex-direction: column;
            align-items: flex-start !important;
            gap: 10px;
        }

        #transactionDetailModal #trxProductItems .d-flex .text-success {
            align-self: flex-end;
        }

        #transactionDetailModal .modal-footer .d-flex {
            flex-direction: column;
            gap: 10px;
        }

        #transactionDetailModal .modal-footer .btn {
            width: 100%;
        }
    }
</style>

<div class="modal fade" id="transactionDetailModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 rounded-4">

            <!-- HEADER -->
            <div class="modal-header border-0 pb-2">
                <h5 class="fw-bold mb-0">Detail Transaksi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body pt-0 px-4 pb-4">

                <!-- STATUS -->
                <div class="border-bottom pb-3 mb-3">
                    <div class="fw-semibold fs-5" id="trxStatus">
                        Menunggu Pembayaran
                    </div>
                </div>

                <!-- ORDER INFO -->
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">No. Pesanan</span>
                    <span class="fw-semibold text-success" id="trxOrderId">-</span>
                </div>

                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted">Tanggal Pesanan</span>
                    <span id="trxDate">-</span>
                </div>

                <hr class="my-3">

                <!-- PRODUCT -->
                <div class="mb-3">
                    <h6 class="fw-bold mb-3">Detail Produk</h6>
                    <div id="trxProductItems"></div>
                </div>

                <hr class="my-3">

                <!-- PAYMENT DETAIL -->
                <div class="mb-3">
                    <h6 class="fw-bold mb-3">Rincian Pembayaran</h6>

                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Metode Pembayaran</span>
                        <span id="trxPaymentMethod" class="fw-semibold">-</span>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Subtotal Harga Barang</span>
                        <span id="trxSubtotal">-</span>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Total Ongkos Kirim</span>
                        <span id="trxShipping">-</span>
                    </div>

                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Biaya Jasa Aplikasi</span>
                        <span id="trxFee">-</span>
                    </div>
                </div>

                <hr class="my-3">

                <!-- TOTAL -->
                <div class="d-flex justify-content-between fw-bold fs-5 mb-4">
                    <span>Total Belanja</span>
                    <span id="trxTotal" class="text-success">-</span>
                </div>

            </div>

            <!-- FOOTER WITH ACTION BUTTONS -->
            <div class="modal-footer border-0 px-4 pb-4 pt-0">
                <div class="d-flex justify-content-between gap-3 w-100">
                    <button class="btn btn-danger text-center px-4 py-2 fw-semibold rounded-3 flex-grow-1"
                        id="trxCancelBtn" style="display: none;">
                        Batalkan Pesanan
                    </button>
                    <button class="btn btn-success text-center px-4 py-2 fw-semibold rounded-3 flex-grow-1"
                        id="trxPayBtn" style="display: none;">
                        </i> Bayar Sekarang
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Variabel untuk menyimpan orderId yang sedang diproses
    let currentOrderForPayment = null;
    let snapTokenCallback = null;

    // Modal Transaction Detail Handler
    document.addEventListener('click', async function(e) {
        const btn = e.target.closest('.btn-transaction-detail');
        if (!btn) return;

        const orderId = btn.dataset.id;
        const token = localStorage.getItem('token');

        if (!token) {
            console.error('Token tidak ditemukan');
            showAlert('Silakan login terlebih dahulu', 'error');
            return;
        }

        // Show loading state
        const modalElement = document.getElementById('transactionDetailModal');
        const productContainer = document.getElementById('trxProductItems');
        if (productContainer) {
            productContainer.innerHTML = '<div class="text-center py-3">Memuat data...</div>';
        }

        try {
            // Ambil detail order
            const orderRes = await fetch(`/api/orders/${orderId}`, {
                headers: {
                    Authorization: `Bearer ${token}`,
                    Accept: 'application/json'
                }
            });

            const orderJson = await orderRes.json();
            const order = orderJson.data;

            if (!order) {
                throw new Error('Data order tidak ditemukan');
            }

            console.log('ORDER DETAIL:', order);

            // Isi data ke modal
            // Status - gunakan status_label dari response
            const statusHtml = getStatusHtml(order.status, order.delivery_method);
            document.getElementById('trxStatus').innerHTML = statusHtml;

            // No. Pesanan & Tanggal
            document.getElementById('trxOrderId').innerText = order.order_number || '#' + order.id;
            document.getElementById('trxDate').innerText = formatDateIndonesia(order.created_at);

            // Tampilkan semua produk dari order.items
            const items = order.items || [];
            if (productContainer) {
                if (items.length === 0) {
                    productContainer.innerHTML = '<div class="text-muted">Tidak ada produk</div>';
                } else {
                    let productsHtml = '';
                    items.forEach((item, index) => {
                        const productName = item.name || item.product_name || '-';
                        const quantity = item.quantity || 1;
                        const price = item.unit_price || item.price || 0;
                        const image = item.image || item.product_image || '/images/placeholder.png';
                        const productId = item.product_id;

                        productsHtml += `
                            <div class="d-flex justify-content-between align-items-start mb-3 pb-2 ${index !== items.length - 1 ? 'border-bottom' : ''}">
                                <div class="d-flex gap-3">
                                    <img src="${image}"
                                        style="width:70px;height:70px;object-fit:cover;border-radius:8px;"
                                        onerror="this.src='/images/placeholder.png'">
                                    <div>
                                        <div class="fw-semibold">${escapeHtml(productName)}</div>
                                        <small class="text-muted">${quantity}x ${formatRupiah(price)}</small>
                                        ${item.unit ? `<div class="text-muted small">Satuan: ${escapeHtml(item.unit)}</div>` : ''}
                                    </div>
                                </div>
                                ${productId ? `<a href="/product/${productId}" class="text-success fw-semibold small text-decoration-none">Lihat Produk →</a>` : ''}
                            </div>
                        `;
                    });
                    productContainer.innerHTML = productsHtml;
                }
            }

            // Rincian Pembayaran - gunakan data dari order.summary
            const summary = order.summary || {};
            const payment = order.payment || {};

            document.getElementById('trxPaymentMethod').innerText = payment.method || order
                .payment_method || '-';
            document.getElementById('trxSubtotal').innerHTML = summary.subtotal_format || formatRupiah(
                summary.subtotal);
            document.getElementById('trxShipping').innerHTML = summary.shipping_cost_format || formatRupiah(
                summary.shipping_cost);
            document.getElementById('trxFee').innerHTML = summary.other_fee_format || formatRupiah(summary
                .other_fee);
            document.getElementById('trxTotal').innerHTML = summary.total_format || formatRupiah(summary
                .total);

            // Tampilkan tombol aksi berdasarkan status
            const cancelBtn = document.getElementById('trxCancelBtn');
            const payBtn = document.getElementById('trxPayBtn');

            // Reset event listeners
            if (cancelBtn) {
                cancelBtn.onclick = null;
            }
            if (payBtn) {
                payBtn.onclick = null;
            }

            // Status: awaiting_payment - tampilkan tombol bayar dan batalkan
            if (order.status === 'awaiting_payment' || order.status === 'pending') {
                if (cancelBtn) {
                    cancelBtn.style.display = 'flex';
                    cancelBtn.onclick = () => cancelOrder(order.id);
                }
                if (payBtn) {
                    payBtn.style.display = 'flex';
                    payBtn.onclick = () => processPayment(order.id);
                }
            }
            // Status: paid - hanya tampilkan tombol batalkan (jika bisa)
            else if (order.status === 'paid' && order.can_cancel === true) {
                if (cancelBtn) {
                    cancelBtn.style.display = 'flex';
                    cancelBtn.onclick = () => cancelOrder(order.id);
                }
                if (payBtn) {
                    payBtn.style.display = 'none';
                }
            }
            // Status lainnya - sembunyikan semua tombol
            else {
                if (cancelBtn) cancelBtn.style.display = 'none';
                if (payBtn) payBtn.style.display = 'none';
            }

            // Tampilkan modal
            const modal = bootstrap.Modal.getOrCreateInstance(modalElement);
            modal.show();

        } catch (err) {
            console.error('Error loading order detail:', err);
            if (productContainer) {
                productContainer.innerHTML =
                    '<div class="text-center text-danger py-3">Gagal memuat data pesanan</div>';
            }
            showAlert('Gagal memuat detail pesanan. Silakan coba lagi.', 'error');
        }
    });

    // Fungsi untuk memproses pembayaran dengan Midtrans
    async function processPayment(orderId) {
        const token = localStorage.getItem('token');
        const payBtn = document.getElementById('trxPayBtn');

        if (!token) {
            showAlert('Silakan login terlebih dahulu', 'error');
            return;
        }

        // Simpan orderId untuk referensi
        currentOrderForPayment = orderId;

        // Tampilkan loading
        if (payBtn) {
            payBtn.disabled = true;
            payBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Memproses...';
        }

        try {
            // Ambil snap token dari backend
            const snapRes = await fetch(`/api/orders/${orderId}/payment/snap-token`, {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            });

            const snapJson = await snapRes.json();

            if (!snapRes.ok || !snapJson.success) {
                throw new Error(snapJson.message || 'Gagal membuat token pembayaran');
            }

            const {
                snap_token,
                client_key,
                snap_url
            } = snapJson.data;

            // Pastikan snap sudah dimuat
            if (typeof window.snap === 'undefined') {
                // Load Snap script jika belum ada
                await loadSnapScript(client_key);
            }

            // Buka popup pembayaran Midtrans
            window.snap.pay(snap_token, {
                onSuccess: function(result) {
                    console.log('Payment Success:', result);
                    showAlert('Pembayaran berhasil!', 'success');
                    // Tutup modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById(
                        'transactionDetailModal'));
                    if (modal) modal.hide();
                    // Refresh halaman atau load ulang data pesanan
                    setTimeout(() => {
                        if (typeof loadWaitingOrders === 'function') {
                            loadWaitingOrders();
                        } else {
                            location.reload();
                        }
                    }, 1500);
                },
                onPending: function(result) {
                    console.log('Payment Pending:', result);
                    showAlert('Menunggu konfirmasi pembayaran', 'info');
                    // Tutup modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById(
                        'transactionDetailModal'));
                    if (modal) modal.hide();
                    setTimeout(() => {
                        if (typeof loadWaitingOrders === 'function') {
                            loadWaitingOrders();
                        } else {
                            location.reload();
                        }
                    }, 1500);
                },
                onError: function(result) {
                    console.error('Payment Error:', result);
                    showAlert('Pembayaran gagal. Silakan coba lagi.', 'error');
                    if (payBtn) {
                        payBtn.disabled = false;
                        payBtn.innerHTML = '</i> Bayar Sekarang';
                    }
                },
                onClose: function() {
                    console.log('Payment popup closed');
                    // User menutup popup tanpa menyelesaikan pembayaran
                    if (payBtn) {
                        payBtn.disabled = false;
                        payBtn.innerHTML = '</i> Bayar Sekarang';
                    }
                    showAlert('Pembayaran dibatalkan', 'info');
                }
            });

        } catch (err) {
            console.error('Payment error:', err);
            showAlert(err.message || 'Terjadi kesalahan saat memproses pembayaran', 'error');
            if (payBtn) {
                payBtn.disabled = false;
                payBtn.innerHTML = '<i class="bi bi-credit-card"></i> Bayar Sekarang';
            }
        }
    }

    // Fungsi untuk memuat script Snap Midtrans
    function loadSnapScript(clientKey) {
        return new Promise((resolve, reject) => {
            // Cek apakah sudah ada
            if (typeof window.snap !== 'undefined') {
                resolve();
                return;
            }

            const script = document.createElement('script');
            script.src = 'https://app.sandbox.midtrans.com/snap/snap.js';
            script.setAttribute('data-client-key', clientKey);
            script.onload = () => {
                console.log('Snap script loaded');
                resolve();
            };
            script.onerror = () => {
                reject(new Error('Gagal memuat Midtrans Snap'));
            };
            document.head.appendChild(script);
        });
    }

    // Fungsi untuk mendapatkan HTML status berdasarkan status order
    function getStatusHtml(status, deliveryMethod) {
        const statusMap = {
            'awaiting_payment': '<span class="text-warning">⏳ Menunggu Pembayaran</span>',
            'pending': '<span class="text-warning">⏳ Menunggu Pembayaran</span>',
            'paid': '<span class="text-info">✅ Sudah Dibayar - Diproses</span>',
            'shipped': deliveryMethod === 'pickup' ?
                '<span class="text-primary">📦 Siap Diambil</span>' :
                '<span class="text-primary">🚚 Dikirim</span>',
            'ready_to_pickup': '<span class="text-primary">📦 Siap Diambil</span>',
            'completed': '<span class="text-success">✓ Selesai</span>',
            'cancelled': '<span class="text-danger">✗ Dibatalkan</span>',
            'refunded': '<span class="text-danger">💰 Dikembalikan</span>'
        };
        return statusMap[status] || status;
    }

    // Fungsi cancel order
    async function cancelOrder(orderId) {
        if (!confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')) return;

        const token = localStorage.getItem('token');
        const cancelBtn = document.getElementById('trxCancelBtn');

        if (cancelBtn) {
            cancelBtn.disabled = true;
            cancelBtn.innerHTML = 'Memproses...';
        }

        try {
            const res = await fetch(`/api/orders/${orderId}/cancel`, {
                method: 'POST',
                headers: {
                    Authorization: `Bearer ${token}`,
                    Accept: 'application/json',
                    'Content-Type': 'application/json'
                }
            });

            const result = await res.json();

            if (result.success) {
                showAlert('Pesanan berhasil dibatalkan', 'success');
                // Tutup modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('transactionDetailModal'));
                if (modal) modal.hide();
                // Refresh daftar pesanan
                if (typeof loadWaitingOrders === 'function') {
                    loadWaitingOrders();
                } else {
                    location.reload();
                }
            } else {
                showAlert(result.message || 'Gagal membatalkan pesanan', 'error');
                if (cancelBtn) {
                    cancelBtn.disabled = false;
                    cancelBtn.innerHTML = 'Batalkan Pesanan';
                }
            }
        } catch (err) {
            console.error('Cancel error:', err);
            showAlert('Terjadi kesalahan, silakan coba lagi', 'error');
            if (cancelBtn) {
                cancelBtn.disabled = false;
                cancelBtn.innerHTML = 'Batalkan Pesanan';
            }
        }
    }

    // Fungsi format Rupiah
    function formatRupiah(number) {
        if (!number && number !== 0) return 'Rp 0';
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(number);
    }

    // Fungsi format date Indonesia
    function formatDateIndonesia(dateString) {
        if (!dateString) return '-';
        const date = new Date(dateString);
        const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September',
            'Oktober', 'November', 'Desember'
        ];

        const dayName = days[date.getDay()];
        const day = date.getDate();
        const month = months[date.getMonth()];
        const year = date.getFullYear();
        const hours = String(date.getHours()).padStart(2, '0');
        const minutes = String(date.getMinutes()).padStart(2, '0');

        return `${dayName}, ${day} ${month} ${year} ${hours}:${minutes} WIB`;
    }

    // Fungsi escape HTML untuk keamanan
    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Fungsi show alert sederhana
    function showAlert(message, type = 'info') {
        let alertContainer = document.querySelector('.custom-alert-container');
        if (!alertContainer) {
            alertContainer = document.createElement('div');
            alertContainer.className = 'custom-alert-container';
            alertContainer.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 9999;
                max-width: 350px;
            `;
            document.body.appendChild(alertContainer);
        }

        const bgColor = type === 'success' ? '#28a745' : (type === 'error' ? '#dc3545' : (type === 'info' ? '#17a2b8' :
            '#ffc107'));
        const alertId = 'alert-' + Date.now();

        const alertHtml = `
            <div id="${alertId}" class="custom-alert" style="
                background: ${bgColor};
                color: white;
                padding: 12px 20px;
                border-radius: 8px;
                margin-bottom: 10px;
                box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                animation: slideIn 0.3s ease;
                font-size: 14px;
            ">
                ${message}
            </div>
        `;

        alertContainer.insertAdjacentHTML('beforeend', alertHtml);

        setTimeout(() => {
            const alertElement = document.getElementById(alertId);
            if (alertElement) {
                alertElement.style.animation = 'slideOut 0.3s ease';
                setTimeout(() => alertElement.remove(), 300);
            }
        }, 3000);
    }

    // Tambahkan CSS animation untuk alert jika belum ada
    if (!document.querySelector('#alert-animation-style')) {
        const style = document.createElement('style');
        style.id = 'alert-animation-style';
        style.textContent = `
            @keyframes slideIn {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }

            @keyframes slideOut {
                from {
                    transform: translateX(0);
                    opacity: 1;
                }
                to {
                    transform: translateX(100%);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
    }
</script>
