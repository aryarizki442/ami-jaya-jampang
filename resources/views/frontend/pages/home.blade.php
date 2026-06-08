@extends('app')

@section('title', 'Home')

@section('hero')
    <section class="hero-bg">
        <section class="hero">
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

                        <a href="{{ route('all-product', ['category' => $category->id]) }}"
                            class="btn-category w-100 text-decoration-none d-block">

                            <img src="{{ $category->image }}" class="img-fluid rounded-circle mb-2"
                                alt="{{ $category->name }}">

                            <p class="mb-0">Beras {{ $category->name }}</p>

                        </a>

                    </div>
                @empty
                    <div class="col-12">
                        <p class="text-muted">Kategori tidak tersedia</p>
                    </div>
                @endforelse

            </div>
        </section>


        <section class="best mb-4">
            <h2>Beras Terlaris</h2>

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
        const hero = document.querySelector('.hero');
        const dots = document.querySelectorAll('.hero-dots span');

        const images = [
            '/images/home/bg-hero1.png',
            '/images/home/bg-hero2.png',
            '/images/home/bg-hero3.png'
        ];

        let currentIndex = 0;

        function changeSlide(index) {

            hero.style.backgroundImage =
                `url('${images[index]}')`;

            dots.forEach(dot =>
                dot.classList.remove('active')
            );

            dots[index].classList.add('active');

            currentIndex = index;
        }

        // Auto slide tiap 4 detik
        setInterval(() => {

            let next = currentIndex + 1;

            if (next >= images.length) {
                next = 0;
            }

            changeSlide(next);

        }, 4000);

        // Klik dot
        dots.forEach((dot, index) => {
            dot.addEventListener('click', () => {
                changeSlide(index);
            });
        });
    </script>
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
