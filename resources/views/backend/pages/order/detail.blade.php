@extends('backend.app')

@section('title', 'Detail Pesanan')

@section('content')

    <style>
        .detail-page {
            background: #f5f6fa;
            min-height: 100vh;
            padding: 24px;
        }

        .detail-card {
            background: #fff;
            border-radius: 6px;
            border: 1px solid #e5e7eb;
            overflow: hidden;
        }

        .detail-section {
            padding: 24px;
            border-bottom: 1px solid #e5e7eb;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            gap: 24px;
            margin-bottom: 14px;
            font-size: 15px;
        }

        .detail-row:last-child {
            margin-bottom: 0;
        }

        .detail-label {
            font-weight: 600;
            color: #111;
        }

        .detail-value {
            color: #111;
            text-align: right;
        }

        .text-muted-custom {
            color: #9ca3af;
        }

        .badge-detail {
            padding: 4px 10px;
            border-radius: 5px;
            font-weight: 500;
            font-size: 14px;
        }

        /* Status Pesanan */
        .badge-awaiting_payment {
            background-color: var(--neutral-50);
            color: var(--neutral-500);
        }

        .badge-paid {
            background-color: var(--warning-50);
            color: var(--warning-500);
        }

        .badge-shipped {
            background-color: var(--info-50);
            color: var(--info-500);
        }

        .badge-completed {
            background-color: var(--success-50);
            color: var(--success-500);
        }

        .badge-cancelled,
        .badge-rejected {
            background-color: var(--danger-50);
            color: var(--danger-500);
        }

        /* Style seragam */
        .badge-awaiting_payment,
        .badge-paid,
        .badge-shipped,
        .badge-completed,
        .badge-cancelled,
        .badge-rejected {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 4px 12px;
            border-radius: 5px;
            font-weight: 500;
            font-size: 12px;
            white-space: nowrap;
        }

        .product-line {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 16px;
            margin-bottom: 12px;
        }

        .btn-green {
            background: #147a4b;
            color: #fff;
            border: none;
            padding: 9px 28px;
            border-radius: 5px;
            font-weight: 600;
            transition: all 0.2s;
        }

        .btn-green:hover {
            background: #0f5e3a;
            cursor: pointer;
        }

        .btn-red {
            background: #ef4444;
            color: #fff;
            border: none;
            padding: 9px 28px;
            border-radius: 5px;
            font-weight: 600;
            transition: all 0.2s;
        }

        .btn-red:hover {
            background: #dc2626;
            cursor: pointer;
        }

        .btn-gray {
            background: #9ca3af;
            color: #fff;
            border: none;
            padding: 9px 28px;
            border-radius: 5px;
            font-weight: 600;
            cursor: not-allowed;
        }

        .bottom-actions {
            display: flex;
            justify-content: flex-end;
            gap: 16px;
            margin-top: 16px;
        }

        /* Loading overlay */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .loading-spinner {
            background: white;
            padding: 20px;
            border-radius: 10px;
            display: flex;
            gap: 10px;
            align-items: center;
        }

        /* Kategori */
        .premium-category {
            background-color: var(--primary-50);
            color: var(--primary-500);
            font-weight: 500;
        }

        .medium-category {
            background-color: var(--secondary-100);
            color: var(--warning-500);
            font-weight: 500;
        }

        .ketan-category {
            background-color: var(--info-50);
            color: var(--info-500);
            font-weight: 500;
        }

        .premium-category,
        .medium-category,
        .ketan-category {
            display: inline-flex;
            align-items: center;
            justify-content: center;

            padding: 4px 10px;
            min-width: 80px;
            /* biar lebar konsisten */
            height: 20px;
            /* biar tinggi sama */
            border-radius: 5px;

            font-weight: 500;
            font-size: 12px;
            white-space: nowrap;
        }

        @media (max-width: 768px) {
            .detail-row {
                flex-direction: column;
                gap: 4px;
            }

            .detail-value {
                text-align: left;
            }

            .bottom-actions {
                flex-direction: column;
            }

            .btn-green,
            .btn-red,
            .btn-gray {
                width: 100%;
            }
        }
    </style>

    @php
        $badgeClass = 'badge-' . $order->status;

        $deliveryText =
            $order->delivery_method === 'delivery' ? 'Dikirim Ke Alamat Pembeli' : 'Pick Up (Ambil ke Lokasi Penjual)';

        $address = $order->address;

        $shippingAddress = $address
            ? trim(
                ($address->detail ?? '') .
                    ' ' .
                    ($address->district ?? '') .
                    ', ' .
                    ($address->city ?? '') .
                    ', ' .
                    ($address->province ?? '') .
                    ' ' .
                    ($address->postal_code ?? ''),
            )
            : '-';

        // Mapping status label
        $statusLabel = match ($order->status) {
            'awaiting_payment' => 'Menunggu Pembayaran',
            'paid' => 'Menunggu Konfirmasi',
            'shipped' => $order->delivery_method === 'pickup' ? 'Dijemput' : 'Dikirim',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            'rejected' => 'Ditolak',
            default => ucfirst($order->status),
        };
    @endphp

    <div class="detail-page">

        <a href="{{ route('admin.order') }}"
            class="d-inline-flex align-items-center gap-2 text-decoration-none text-dark mb-3">
            <span class="iconify" data-icon="mdi:arrow-left" style="font-size:24px;"></span>
            <span>Kembali</span>
        </a>

        <h5 class="fw-bold mb-4">Detail Pesanan</h5>

        <div class="detail-card">

            {{-- ID PESANAN --}}
            <div class="detail-section">
                <div class="detail-row">
                    <div class="detail-label">ID Pesanan</div>
                    <div class="detail-value">{{ $order->order_number ?? '-' }}</div>
                </div>
            </div>

            {{-- DATA PEMBELI --}}
            <div class="detail-section">
                <div class="detail-row">
                    <div class="detail-label">Nama Pembeli</div>
                    <div class="detail-value">{{ $order->user->name ?? '-' }}</div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">No. Telepon</div>
                    <div class="detail-value">{{ $order->user->phone ?? '-' }}</div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">Email</div>
                    <div class="detail-value">{{ $order->user->email ?? '-' }}</div>
                </div>
            </div>

            {{-- ALAMAT --}}
            <div class="detail-section">
                <div class="detail-label mb-1">Alamat Pengiriman</div>
                <div class="text-muted-custom">
                    {{ $shippingAddress }}
                </div>
            </div>

            {{-- METODE PEMBAYARAN & STATUS --}}
            <div class="detail-section">
                <div class="detail-row">
                    <div class="detail-label">Metode Pembayaran</div>
                    <div class="detail-value">{{ $order->payment?->paymentMethod?->name ?? '-' }}</div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">Status Pesanan</div>
                    <div class="detail-value">
                        <span class="badge-detail {{ $badgeClass }}">
                            {{ $statusLabel }}
                        </span>
                    </div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">Tanggal Pemesanan</div>
                    <div class="detail-value text-muted-custom">
                        {{ $order->created_at->format('d M Y, H.i') }} WIB
                    </div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">Tanggal Pesanan Tiba</div>
                    <div class="detail-value text-muted-custom">
                        {{ $order->estimated_arrival ?? '-' }}
                    </div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">Jenis Pengantaran</div>
                    <div class="detail-value text-muted-custom">
                        <span class="iconify text-dark me-1" data-icon="mdi:truck-outline"></span>
                        {{ $deliveryText }}
                    </div>
                </div>
            </div>

            {{-- PRODUK --}}
            <div class="detail-section">
                <h6 class="fw-bold mb-3">Produk Yang Dibeli</h6>

                {{-- SCROLLABLE area untuk list produk --}}
                <div class="px-3"
                    style="max-height: 200px; overflow-y: auto; border-top: 1px solid #eee; border-bottom: 1px solid #eee; padding: 8px 0;">
                    @foreach ($order->items as $item)
                        @php
                            // Tentukan class badge berdasarkan kategori
                            $categoryClass = '';
                            $categoryName = strtolower($item->product->category->name ?? 'premium');

                            if ($categoryName === 'premium') {
                                $categoryClass = 'premium-category fw-normal';
                            } elseif ($categoryName === 'medium') {
                                $categoryClass = 'medium-category fw-normal';
                            } elseif ($categoryName === 'ketan') {
                                $categoryClass = 'ketan-category fw-normal';
                            } else {
                                $categoryClass = 'premium-category fw-normal';
                            }
                        @endphp

                        {{-- Baris 1: Nama produk + qty di kiri, badge di kanan --}}
                        <div
                            style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                            <div>
                                <strong>{{ $item->product_name }}</strong>
                                <span class="text-muted-custom ms-2">{{ $item->quantity }} x</span>
                            </div>
                            <div>
                                <span class="badge {{ $categoryClass }}">
                                    {{ $item->product->category->name ?? 'Premium' }}
                                </span>
                            </div>
                        </div>

                        {{-- Baris 2: Total Harga (kiri) dan perhitungan (kanan) --}}
                        <div style="display: flex; justify-content: space-between; margin-bottom: 20px;">
                            <div class="text-muted-custom">Total Harga</div>
                            <div class="text-end">
                                <div class="small">{{ $item->quantity }} x Rp.
                                    {{ number_format($item->unit_price, 0, ',', '.') }}</div>
                                <strong>Rp. {{ number_format($item->subtotal, 0, ',', '.') }}</strong>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Ringkasan ongkir & biaya lain --}}
                <div style="margin-top: 16px;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                        <div>Total ongkos Kirim</div>
                        <div>
                            {{ ($order->shipping_cost ?? 0) > 0 ? 'Rp. ' . number_format($order->shipping_cost, 0, ',', '.') : '-' }}
                        </div>
                    </div>
                    @if (($order->other_fee ?? 0) > 0)
                        <div style="display: flex; justify-content: space-between;">
                            <div>Biaya Layanan</div>
                            <div>Rp. {{ number_format($order->other_fee, 0, ',', '.') }}</div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- TOTAL --}}
            <div class="detail-section border-bottom-0">
                <div class="product-line mb-0">
                    <strong>Total Bayar</strong>
                    <strong class="text-success">
                        Rp. {{ number_format($order->total, 0, ',', '.') }}
                    </strong>
                </div>
            </div>
        </div>

        {{-- TOMBOL AKSI --}}
        <div class="bottom-actions" id="actionButtons">
            @if ($order->status === 'paid')
                <button class="btn-red" data-action="reject">Tolak Pembelian</button>
                <button class="btn-green" data-action="confirm">Konfirmasi Pembelian</button>
            @elseif ($order->status === 'shipped')
                {{-- TAMBAHKAN INI: Tombol Selesai untuk status Dikirim/Dijemput --}}
                <button class="btn-green" data-action="complete">Selesai</button>
            @elseif ($order->status === 'awaiting_payment')
                <button class="btn-gray" disabled>Menunggu Pembayaran</button>
            @elseif ($order->status === 'completed')
                <button class="btn-gray" disabled>Pesanan Selesai</button>
            @elseif ($order->status === 'cancelled')
                <button class="btn-gray" disabled>Pesanan Dibatalkan</button>
            @elseif ($order->status === 'rejected')
                <button class="btn-gray" disabled>Pesanan Ditolak</button>
            @endif
        </div>

    </div>

    <!-- MODAL KONFIRMASI -->
    <div class="modal fade" id="confirmModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header justify-content-center">
                    <h5 class="fw-semibold m-0" id="modalTitle">Konfirmasi</h5>
                </div>
                <div class="modal-body text-center" id="modalMessage">
                    Apakah Anda yakin ingin mengkonfirmasi pembelian ini?
                </div>
                <div class="modal-footer justify-content-center gap-2">
                    <button class="btn btn-delete-second" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-delete-main" id="confirmActionBtn" style="background: #147a4b;">Ya,
                        Konfirmasi</button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL TOLAK -->
    <div class="modal fade" id="rejectModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header justify-content-center">
                    <h5 class="fw-semibold m-0">Tolak Pembelian</h5>
                </div>
                <div class="modal-body">
                    <p class="text-center mb-3">Apakah Anda yakin ingin menolak pembelian ini?</p>
                    <label class="form-label fw-semibold">Alasan Penolakan (Opsional)</label>
                    <textarea id="rejectReason" class="form-control" rows="3" placeholder="Masukkan alasan penolakan..."></textarea>
                </div>
                <div class="modal-footer justify-content-center gap-2">
                    <button class="btn btn-delete-second" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-delete-main" id="confirmRejectBtn">Ya, Tolak</button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL SELESAI -->
    <div class="modal fade" id="completeModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header justify-content-center">
                    <h5 class="fw-semibold m-0">Selesaikan Pesanan</h5>
                </div>
                <div class="modal-body text-center">
                    <p class="mb-0">Apakah Anda yakin pesanan ini sudah selesai?</p>
                </div>
                <div class="modal-footer justify-content-center gap-2">
                    <button class="btn btn-delete-second" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-delete-main" id="confirmCompleteBtn" style="background: #147a4b;">Ya,
                        Selesai</button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL SUKSES -->
    <div class="modal fade" id="successModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content p-5 text-center">
                <div class="d-flex justify-content-center mb-3">
                    <div class="d-flex align-items-center justify-content-center rounded-circle"
                        style="width:50px; height:50px; background:#22C55E;">
                        <i class="iconify text-white fs-1" data-icon="iconamoon:check-bold"></i>
                    </div>
                </div>
                <p class="mb-0 fw-medium" id="successMessage">Berhasil Mengubah Status Pesanan</p>
            </div>
        </div>
    </div>

    <!-- MODAL ERROR -->
    <div class="modal fade" id="errorModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content p-5 text-center">
                <div class="d-flex justify-content-center mb-3">
                    <div class="d-flex align-items-center justify-content-center rounded-circle"
                        style="width:50px; height:50px; background:#EF4444;">
                        <i class="iconify text-white fs-1" data-icon="ic:baseline-error"></i>
                    </div>
                </div>
                <p class="mb-0 fw-medium" id="errorMessage">Gagal Mengubah Status</p>
            </div>
        </div>
    </div>

    <script src="https://code.iconify.design/3/3.1.0/iconify.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const orderId = {{ $order->id }};
            let currentAction = null;
            let currentStatus = null;

            // Loading overlay
            function showLoading() {
                let overlay = document.querySelector('.loading-overlay');
                if (!overlay) {
                    overlay = document.createElement('div');
                    overlay.className = 'loading-overlay';
                    overlay.innerHTML = `
                    <div class="loading-spinner">
                        <div class="spinner-border text-success" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <span>Memproses...</span>
                    </div>
                `;
                    document.body.appendChild(overlay);
                }
                overlay.style.display = 'flex';
            }

            function hideLoading() {
                const overlay = document.querySelector('.loading-overlay');
                if (overlay) overlay.style.display = 'none';
            }

            function showSuccess(message) {
                document.getElementById('successMessage').textContent = message;
                const modal = new bootstrap.Modal(document.getElementById('successModal'));
                modal.show();
                setTimeout(() => {
                    modal.hide();
                    window.location.href = "{{ route('admin.order') }}";
                }, 1500);
            }

            function showError(message) {
                document.getElementById('errorMessage').textContent = message;
                const modal = new bootstrap.Modal(document.getElementById('errorModal'));
                modal.show();
                setTimeout(() => modal.hide(), 2000);
            }

            // Update status function
            function updateStatus(newStatus, reason = null) {
                showLoading();

                let url = `/api/admin/orders/${orderId}/status`;
                let body = {
                    status: newStatus
                };

                if (reason) {
                    body.cancel_reason = reason;
                }

                fetch(url, {
                        method: 'PATCH', // atau 'PUT' sesuai route Anda
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                        },
                        body: JSON.stringify(body)
                    })
                    .then(async res => {
                        const data = await res.json();
                        if (!res.ok || !data.success) {
                            throw new Error(data.message || 'Gagal mengubah status');
                        }
                        return data;
                    })
                    .then(data => {
                        hideLoading();
                        let successMsg = '';
                        if (newStatus === 'shipped') {
                            successMsg = 'Berhasil Mengkonfirmasi Pembelian';
                        } else if (newStatus === 'cancelled') {
                            successMsg = 'Berhasil Menolak Pembelian';
                        } else if (newStatus === 'completed') {
                            successMsg = 'Berhasil Menyelesaikan Pesanan'; // TAMBAHKAN INI
                        }
                        showSuccess(successMsg);
                    })
                    .catch(err => {
                        hideLoading();
                        showError(err.message);
                    });
            }

            // Button handlers
            const confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));
            const rejectModal = new bootstrap.Modal(document.getElementById('rejectModal'));
            const completeModal = new bootstrap.Modal(document.getElementById('completeModal'));

            // Konfirmasi Pembelian (paid -> shipped)
            document.querySelectorAll('[data-action="confirm"]').forEach(btn => {
                btn.addEventListener('click', () => {
                    document.getElementById('modalTitle').textContent = 'Konfirmasi Pembelian';
                    document.getElementById('modalMessage').innerHTML =
                        'Apakah Anda yakin ingin mengkonfirmasi pembelian ini?<br><small class="text-muted">Status akan berubah menjadi "Dikirim/Dijemput"</small>';
                    document.getElementById('confirmActionBtn').style.background = '#147a4b';
                    document.getElementById('confirmActionBtn').textContent = 'Ya, Konfirmasi';
                    currentAction = 'confirm';
                    confirmModal.show();
                });
            });

            // Tolak Pembelian (paid -> cancelled)
            document.querySelectorAll('[data-action="reject"]').forEach(btn => {
                btn.addEventListener('click', () => {
                    currentAction = 'reject';
                    rejectModal.show();
                });
            });

            // Selesai (shipped -> completed)
            document.querySelectorAll('[data-action="complete"]').forEach(btn => {
                btn.addEventListener('click', () => {
                    document.getElementById('modalTitle').textContent = 'Selesaikan Pesanan';
                    document.getElementById('modalMessage').innerHTML =
                        'Apakah Anda yakin pesanan ini sudah selesai?<br><small class="text-muted">Status akan berubah menjadi "Selesai"</small>';
                    document.getElementById('confirmActionBtn').style.background = '#147a4b';
                    document.getElementById('confirmActionBtn').textContent = 'Ya, Selesai';
                    currentAction = 'complete';
                    confirmModal.show();
                });
            });

            // Confirm action button
            document.getElementById('confirmActionBtn')?.addEventListener('click', () => {
                confirmModal.hide();
                if (currentAction === 'confirm') {
                    updateStatus('shipped');
                } else if (currentAction === 'complete') {
                    updateStatus('completed');
                }
            });

            // Confirm reject button
            document.getElementById('confirmRejectBtn')?.addEventListener('click', () => {
                const reason = document.getElementById('rejectReason').value;
                rejectModal.hide();
                updateStatus('cancelled', reason);
            });
        });
    </script>

@endsection
