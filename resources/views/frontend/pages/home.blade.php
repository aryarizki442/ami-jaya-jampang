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

                @forelse ($categories as $category)
                    <div class="col-md-4 col-12 item mb-3">

                        <button class="btn-category w-100" data-filter="{{ $category->slug ?? $category->id }}">

                            <img src="{{ $category->image }}" class="img-fluid rounded-circle mb-2"
                                alt="{{ $category->name }}">


                            <p class="mb-0">Beras {{ $category->name }}</p>

                        </button>

                    </div>
                @empty
                    <div class="col-12">
                        <p class="text-muted">Kategori tidak tersedia</p>
                    </div>
                @endforelse

            </div>
        </section>


        <section class="best mb-4">
            <h2>Beras Rekomendasi</h2>

            <div class="best-slider">
                <div class="best-track">

                    @forelse ($recommendedProducts as $product)
                        <div class="best-col">
                            <a href="{{ route('detail-product', $product->slug) }}"
                                class="text-decoration-none text-dark d-block h-100">
                                <div class="best-card rounded">

                                    <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('images/home/category/beras-putih.png') }}"
                                        class="img-fluid" alt="{{ $product->name }}">
                                    <div class="best-body">
                                        <div class="rating mb-3">★★★★★</div>

                                        <p class="best-title mb-3">
                                            {{ $product->weight ?? '1 Liter' }} {{ $product->name }}<br>
                                        </p>

                                        <div class="best-footer">
                                            <span class="harga">
                                                Rp. {{ number_format($product->price, 0, ',', '.') }}
                                            </span>

                                            <span class="terjual">
                                                Tersedia {{ $product->stock ?? 0 }}
                                            </span>
                                        </div>
                                    </div>

                                </div>
                            </a>
                        </div>
                    @empty
                        <p class="text-muted">Belum ada produk rekomendasi</p>
                    @endforelse

                </div>
            </div>
        </section>

        <section class="produk-kami mb-4">
            <h2 class="text-center mb-4">Produk</h2>

            <!-- Grid Produk -->
            <div class="row g-3 produk-row">

                @forelse ($products as $product)
                    <div class="produk-col">
                        <a href="{{ route('detail-product', $product->slug) }}" class="text-decoration-none text-dark">
                            <div class="produk-card rounded">

                                <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('images/home/category/beras-putih.png') }}"
                                    class="img-fluid" alt="{{ $product->name }}">
                                <div class="produk-body">

                                    <!-- ⭐ TETAP STATIS SESUAI PERMINTAAN -->
                                    <div class="rating mb-3">★★★★★</div>

                                    <p class="produk-title mb-3">
                                        {{ $product->weight ?? '1 Liter' }} {{ $product->name }}<br>
                                    </p>

                                    <div class="produk-footer">
                                        <span class="harga">
                                            Rp. {{ number_format($product->price, 0, ',', '.') }}
                                        </span>

                                        <span class="terjual">
                                            Tersedia {{ $product->stock ?? 0 }}
                                        </span>
                                    </div>

                                </div>

                            </div>
                        </a>
                    </div>
                @empty
                    <div class="col-12 text-center">
                        <p class="text-muted">Produk belum tersedia</p>
                    </div>
                @endforelse

            </div>

            <!-- Button -->
            <div class="text-center mt-5">
                <a href="{{ route('all-product') }}" class="btn btn-second px-5">
                    Lihat Lebih Banyak
                </a>
            </div>
        </section>

    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const slider = document.querySelector(".best-slider");
            const track = document.querySelector(".best-track");
            const cards = document.querySelectorAll(".best-col");

            const cardCount = cards.length; // 15
            const speed = 0.5; // kecepatan (1 = standar)

            // Clone card agar infinite
            cards.forEach(card => {
                track.appendChild(card.cloneNode(true));
            });

            let position = 0;
            let isPaused = false;
            let cardWidth = cards[0].offsetWidth;

            function animate() {
                if (!isPaused) {
                    position += speed;
                    track.style.transform = `translateX(-${position}px)`;

                    // Reset halus setelah card ke-15
                    if (position >= cardWidth * cardCount) {
                        track.style.transition = "none";
                        position = 0;
                        track.style.transform = "translateX(0)";
                        track.offsetHeight; // force repaint
                        track.style.transition = "transform 0.1s linear";
                    }
                }

                requestAnimationFrame(animate);
            }

            // Hover → pause
            slider.addEventListener("mouseenter", () => {
                isPaused = true;
            });

            // Keluar hover → jalan lagi
            slider.addEventListener("mouseleave", () => {
                isPaused = false;
            });

            track.style.transition = "transform 0.1s linear";
            animate();
        });
    </script>
@endsection
