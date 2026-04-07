@extends('app')

@section('title', 'Detail Payment')

@section('content')
    <style>
        .title-payment {
            font-size: 28px;
        }

        .payment-card {
            border-radius: 8px;
            border: none;
            font-size: 14px;
        }

        hr {
            margin: 18px 0;
            opacity: 0.3;
        }

        .text-success {
            color: #1a8f5b !important;
        }

        .custom-border {
            border-bottom: 1px solid #000000;
            padding-bottom: 1rem;
            /* jarak ke border */
            margin-bottom: 1rem;
            /* setara mb-3 */
        }
    </style>
    <section>
        <h5 class="fw-bold mb-4 title-payment">Detail Payment</h5>
        <div class=" payment-card">
            <div class="row mb-2">
                <div class="col fw-semibold">Total Harga</div>
                <div class="col text-end fw-semibold">Rp. 100.000</div>
            </div>

            <div class="row custom-border">
                <div class="col text-muted">Total ongkos Kirim</div>
                <div class="col text-end text-muted">Rp. 50.000</div>
            </div>

            <div class="row mb-2">
                <div class="col fw-bold">Total Bayar</div>
                <div class="col text-end fw-bold text-success">Rp. 150.000</div>
            </div>

            <div class="row custom-border">
                <div class="col text-muted">BCA Virtual Account</div>
                <div class="col text-end text-muted"> <span class="fw-bold">123456789</span> </div>
            </div>

            <!-- Produk -->
            <h6 class="fw-bold mb-3">Produk Yang Dibeli</h6>

            <div class="row mb-2">
                <div class="col fw-semibold">
                    1 Karung Beras Putih Premium Rojo Lele
                </div>
                <div class="col text-end text-muted">
                    Rp. 100.000
                </div>
            </div>

            <div class="row mb-1">
                <div class="col text-muted small">1 X Rp.100.000</div>
                <div class="col"></div>
            </div>

            <div class="row mb-1">
                <div class="col text-muted small">Ongkos Kirim</div>
                <div class="col text-end text-muted">Rp. 50.000</div>
            </div>

            <div class="row mb-3">
                <div class="col text-muted small">Estimasi Tiba Besok</div>
                <div class="col"></div>
            </div>

            <!-- Alamat -->
            <h6 class="fw-bold mb-2">Alamat Pengiriman</h6>
            <p class="text-muted mb-0 small">
                Perumahan Cibinong RT11 RW22 Cibinong, Kabupaten Bogor, Jawa Barat 16345
            </p>

        </div>
        </div>
        </div>
    </section>


@endsection
