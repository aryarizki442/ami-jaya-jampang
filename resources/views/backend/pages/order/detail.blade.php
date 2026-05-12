@extends('backend.app')

@section('title', 'Detail Pesanan')

@section('content')
<!-- <meta name="csrf-token" content="{{ csrf_token() }}"> -->

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

    .badge-awaiting_payment {
        background: #fff4df;
        color: #ff9800;
    }

    .badge-paid,
    .badge-shipped {
        background: #eaf3ff;
        color: #3b82f6;
    }

    .badge-completed {
        background: #e8f7ef;
        color: #15803d;
    }

    .badge-cancelled {
        background: #ffecec;
        color: #ef4444;
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
    }

    .btn-red {
        background: #ef4444;
        color: #fff;
        border: none;
        padding: 9px 28px;
        border-radius: 5px;
        font-weight: 600;
    }

    .bottom-actions {
        display: flex;
        justify-content: flex-end;
        gap: 16px;
        margin-top: 16px;
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
    }
</style>

@php
    $badgeClass = 'badge-' . $order->status;

    $deliveryText = $order->delivery_method === 'delivery'
        ? 'Dikirim Ke Alamat Pembeli'
        : 'Pick Up';

    $address = $order->address;

    $shippingAddress = $address
        ? trim(($address->detail ?? '') . ' ' . ($address->district ?? '') . ', ' . ($address->city ?? '') . ', ' . ($address->province ?? '') . ' ' . ($address->postal_code ?? ''))
        : '-';
@endphp

<div class="detail-page">

    <a href="{{ route('admin.order') }}" class="d-inline-flex align-items-center gap-2 text-decoration-none text-dark mb-3">
        <span class="iconify" data-icon="mdi:arrow-left" style="font-size:24px;"></span>
        <span>Kembali</span>
    </a>

    <h5 class="fw-bold mb-4">Detail Pesanan</h5>

    <div class="detail-card">

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

        {{-- STATUS --}}
        <div class="detail-section">
            <div class="detail-row">
                <div class="detail-label">Status Pesanan</div>
                <div class="detail-value">
                    <span class="badge-detail {{ $badgeClass }}">
                        {{ $order->status_label ?? ucfirst($order->status) }}
                    </span>
                </div>
            </div>

            @if ($order->status === 'cancelled')
                <div class="detail-row">
                    <div class="detail-label">Diminta Oleh</div>
                    <div class="detail-value">{{ $order->cancelled_by ?? 'Pembeli' }}</div>
                </div>
            @endif

            <div class="detail-row">
                <div class="detail-label">Tanggal Pemesanan</div>
                <div class="detail-value text-muted-custom">
                    {{ $order->created_at->format('d M Y, H.i') }} WIB
                </div>
            </div>

            @if ($order->status === 'cancelled')
                <div class="detail-row">
                    <div class="detail-label">Tanggal Pembatalan</div>
                    <div class="detail-value text-muted-custom">
                        {{ $order->updated_at->format('d M Y, H.i') }} WIB
                    </div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">Alasan Penolakan</div>
                    <div class="detail-value">
                        {{ $order->cancel_reason ?? '-' }}
                    </div>
                </div>
            @endif

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
            <h6 class="fw-bold mb-3">Produk Yang Dipesan</h6>

            @foreach ($order->items as $item)
                <div class="product-line">
                    <div>
                        <strong>{{ $item->product_name }}</strong>
                        <span class="text-muted-custom ms-3">{{ $item->quantity }} x</span>
                    </div>

                    <div>
                        <span class="badge-detail badge-completed">
                            {{ $item->product_unit ?? 'Premium' }}
                        </span>
                    </div>
                </div>

                <div class="product-line">
                    <div>
                        <strong>Total Harga</strong>
                        <span class="text-muted-custom ms-3">
                            {{ $item->quantity }} x Rp. {{ number_format($item->unit_price, 0, ',', '.') }}
                        </span>
                    </div>

                    <div>
                        <strong>Rp. {{ number_format($item->subtotal, 0, ',', '.') }}</strong>
                    </div>
                </div>
            @endforeach

            @if (($order->shipping_cost ?? 0) > 0)
                <div class="product-line text-muted-custom">
                    <div>Total ongkos Kirim</div>
                    <div>Rp. {{ number_format($order->shipping_cost, 0, ',', '.') }}</div>
                </div>
            @else
                <div class="product-line text-muted-custom">
                    <div>Total ongkos Kirim</div>
                    <div>-</div>
                </div>
            @endif

            @if (($order->other_fee ?? 0) > 0)
                <div class="product-line text-muted-custom">
                    <div>Biaya Jasa Aplikasi</div>
                    <div>Rp. {{ number_format($order->other_fee, 0, ',', '.') }}</div>
                </div>
            @endif

            <div class="product-line text-muted-custom">
                <div>Metode Pembayaran</div>
                <div>{{ $order->payment?->paymentMethod?->name ?? '-' }}</div>
            </div>
        </div>

        {{-- TOTAL --}}
        <div class="detail-section border-bottom-0">
            <div class="product-line mb-0">
                <strong>Total Pesanan</strong>
                <strong class="text-success">
                    Rp. {{ number_format($order->total, 0, ',', '.') }}
                </strong>
            </div>
        </div>
    </div>

    {{-- TOMBOL AKSI --}}
    <div class="bottom-actions">
        @if ($order->status === 'paid')
            <button class="btn-red">Tolak Pembelian</button>
            <button class="btn-green">Konfirmasi Pembelian</button>
        @elseif ($order->status === 'shipped')
            <button class="btn-green">Selesai</button>
        @endif
    </div>

</div>

<script src="https://code.iconify.design/3/3.1.0/iconify.min.js"></script>
@endsection