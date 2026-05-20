@extends('backend.app')

@section('title', 'Detail Pembayaran')

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

        .badge-pending {
            background: #fff4df;
            color: #ff9800;
        }

        .badge-paid {
            background: #e8f7ef;
            color: #15803d;
        }

        .badge-failed,
        .badge-cancelled {
            background: #ffecec;
            color: #ef4444;
        }

        .badge-expired {
            background: #e5e7eb;
            color: #374151;
        }

        .product-line {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 16px;
            margin-bottom: 12px;
        }

        @media(max-width:768px) {

            .detail-row {
                flex-direction: column;
                gap: 4px;
            }

            .detail-value {
                text-align: left;
            }
        }
    </style>

    @php

        $order = $payment->order;
        $user = $order?->user;
        $address = $order?->address;

        $fullAddress = $address
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
    @endphp

    <div class="detail-page">

        <a href="{{ route('admin.payment.index') }}"
            class="d-inline-flex align-items-center gap-2 text-decoration-none text-dark mb-3">

            <span class="iconify" data-icon="mdi:arrow-left" style="font-size:24px;">
            </span>

            <span>Kembali</span>

        </a>

        <h5 class="fw-bold mb-4">
            Detail Pembayaran
        </h5>

        <div class="detail-card">

            {{-- CUSTOMER --}}
            <div class="detail-section">

                <div class="detail-row">
                    <div class="detail-label">ID Transaksi</div>
                    <div class="detail-value">
                        {{ $payment->id }}
                    </div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">Nomor Pesanan</div>
                    <div class="detail-value">
                        {{ $order?->order_number ?? '-' }}
                    </div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">Nama Pembeli</div>
                    <div class="detail-value">
                        {{ $user?->name ?? '-' }}
                    </div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">No. Telepon</div>
                    <div class="detail-value">
                        {{ $user?->phone ?? '-' }}
                    </div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">Email</div>
                    <div class="detail-value">
                        {{ $user?->email ?? '-' }}
                    </div>
                </div>

            </div>

            {{-- ADDRESS --}}
            <div class="detail-section">

                <div class="detail-label mb-1">
                    Alamat Pengiriman
                </div>

                <div class="text-muted-custom">
                    {{ $fullAddress }}
                </div>

            </div>

            {{-- PAYMENT --}}
            <div class="detail-section">

                <div class="detail-row">
                    <div class="detail-label">Metode Pembayaran</div>
                    <div class="detail-value">
                        {{ $payment->paymentMethod?->name ?? '-' }}
                    </div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">Status Pembayaran</div>

                    <div class="detail-value">

                        <span class="badge-detail badge-{{ $payment->status }}">
                            {{ ucfirst($payment->status) }}
                        </span>

                    </div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">Tanggal Pembayaran</div>

                    <div class="detail-value">
                        {{ $payment->paid_at ? $payment->paid_at->format('d M Y H:i') . ' WIB' : '-' }}
                    </div>
                </div>
            </div>

            {{-- PRODUCTS --}}
            <div class="detail-section">

                <h6 class="fw-bold mb-3">
                    Produk Yang Dibeli
                </h6>

                @foreach ($order?->items ?? [] as $item)
                    <div class="product-line">

                        <div>

                            <strong>
                                {{ $item->product_name }}
                            </strong>

                            <span class="text-muted-custom ms-2">
                                {{ $item->quantity }} x
                            </span>

                        </div>

                        <div>

                            <span class="badge-detail badge-paid">

                                {{ $item->product_unit ?? 'Premium' }}

                            </span>

                        </div>

                    </div>

                    <div class="product-line">

                        <div>

                            <strong>Total Harga</strong>

                            <span class="text-muted-custom ms-2">

                                {{ $item->quantity }} x
                                Rp {{ number_format($item->unit_price, 0, ',', '.') }}

                            </span>

                        </div>

                        <div>

                            <strong>

                                Rp {{ number_format($item->subtotal, 0, ',', '.') }}

                            </strong>

                        </div>

                    </div>
                @endforeach

                <div class="product-line text-muted-custom">

                    <div>Total Ongkos Kirim</div>

                    <div>

                        @if (($order?->shipping_cost ?? 0) > 0)
                            Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}
                        @else
                            -
                        @endif

                    </div>

                </div>

                <div class="product-line text-muted-custom">

                    <div>Biaya Jasa Aplikasi</div>

                    <div>

                        @if (($order?->other_fee ?? 0) > 0)
                            Rp {{ number_format($order->other_fee, 0, ',', '.') }}
                        @else
                            -
                        @endif

                    </div>

                </div>

            </div>

            {{-- TOTAL --}}
            <div class="detail-section border-bottom-0">

                <div class="product-line mb-0">

                    <strong>Total Pembayaran</strong>

                    <strong class="text-success">

                        Rp {{ number_format($payment->amount, 0, ',', '.') }}

                    </strong>

                </div>

            </div>

        </div>

    </div>

    <script src="https://code.iconify.design/3/3.1.0/iconify.min.js"></script>

@endsection
