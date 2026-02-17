@extends('app')

@section('title', 'Home')

@section('hero')
    <section class="hero-bg">
        <section class="hero">
            <div class="hero-content">
                <p>Temukan Berasmu disini</p>
                <h1>Beras Kualitas Terbaik</h1>
                <span>Diskon Hingga 50%</span>
            </div>

            <button class="hero-arrow-wrap left">
                <span class="hero-arrow">
                    <span class="iconify" data-icon="iconoir:arrow-left"></span>
                </span>
            </button>

            <button class="hero-arrow-wrap right">
                <span class="hero-arrow">
                    <span class="iconify rotate" data-icon="iconoir:arrow-left"></span>
                </span>
            </button>

            <div class="hero-dots">
                <span class="active"></span>
                <span></span>
                <span></span>
            </div>
        </section>
    </section>
@endsection

@section('content')

    <div class="container">

        <section class="kategori mb-4">
            <h2>Kategori Beras</h2>

            <div class="row g-0 text-center">

                <!-- Beras Putih Medium -->
                <div class="col-md-4 col-12 item mb-3">
                    <button class="btn-category w-100" data-filter="beras-putih-medium">
                        <img src="{{ asset('images/home/category/beras-putih.png') }}"
                            class="img-fluid rounded-circle mb-2">
                        <p class="mb-0">Beras Putih Medium</p>
                    </button>
                </div>

                <!-- Beras Putih Premium -->
                <div class="col-md-4 col-12 item mb-3">
                    <button class="btn-category w-100" data-filter="beras-putih-premium">
                        <img src="{{ asset('images/home/category/beras-putih.png') }}"
                            class="img-fluid rounded-circle mb-2">
                        <p class="mb-0">Beras Putih Premium</p>
                    </button>
                </div>

                <!-- Beras Ketan -->
                <div class="col-md-4 col-12 item mb-3">
                    <button class="btn-category w-100" data-filter="beras-ketan">
                        <img src="{{ asset('images/home/category/beras-putih.png') }}"
                            class="img-fluid rounded-circle mb-2">
                        <p class="mb-0">Beras Ketan</p>
                    </button>
                </div>

            </div>
        </section>



        <section class="best mb-4">
            <h2 class="text-center mb-4">Beras Terlaris</h2>

            <!-- Grid Produk -->
            <div class="row g-3 best-row">

                @for ($i = 0; $i < 5; $i++)
                    <div class="best-col">
                        <div class="best-card rounded">

                            <img src="{{ asset('images/home/category/beras-putih.png') }}" class="img-fluid">

                            <div class="best-body">
                                <div class="rating">★★★★★</div>

                                <p class="best-title">
                                    1 Liter Beras Premium<br>
                                    Beras Merah Premium Rojolele
                                </p>

                                <div class="best-footer">
                                    <span class="harga">Rp. 30.000</span>
                                    <span class="terjual">Tersedia 100</span>
                                </div>
                            </div>

                        </div>
                    </div>
                @endfor
        </section>

        <section class="produk-kami mb-4">
            <h2 class="text-center mb-4">Produk</h2>

            <!-- Grid Produk -->
            <div class="row g-3 produk-row">
                @for ($i = 0; $i < 15; $i++)
                    <div class="produk-col">
                        <div class="produk-card rounded">

                            <img src="{{ asset('images/home/category/beras-putih.png') }}" class="img-fluid">

                            <div class="produk-body">
                                <div class="rating">★★★★★</div>

                                <p class="produk-title">
                                    1 Liter Beras Premium<br>
                                    Beras Merah Premium Rojolele
                                </p>

                                <div class="produk-footer">
                                    <span class="harga">Rp. 30.000</span>
                                    <span class="terjual">Tersedia 100</span>
                                </div>
                            </div>

                        </div>
                    </div>
                @endfor
            </div>

            <!-- Button -->
            <div class="text-center mt-5">
                <a href="#" class="btn btn-custom-outline-green px-5">
                    Lihat Lebih Banyak
                </a>
            </div>
        </section>

    </div>


@endsection
