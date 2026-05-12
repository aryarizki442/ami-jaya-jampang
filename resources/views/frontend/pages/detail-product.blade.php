@extends('app')

@section('title', 'Deskripsi Produk')

@section('content')
    <style>
        .title-product {
            font-size: 28px;
        }

        .price-product {
            font-size: 26px;
            color: var(--primary-500);
        }

        .qty-group {
            border: 1px solid #adadad;
            border-radius: 5px;
            width: 130px;
            height: 40px;
            overflow: hidden;
            display: flex;
            align-items: center;
        }

        .qty-group .qty-btn {
            background-color: #fff;
            padding: 0 12px;
            font-size: 18px;
            border: none;
            color: #555;
            height: 100%;
        }

        .qty-group .qty-btn {
            transition: all 0.2s ease;
        }

        .qty-group .qty-btn:hover {
            transform: scale(1.2);
        }

        .qty-group .form-control {
            border: none;
            height: 100%;
            text-align: center;
            font-weight: 600;
        }

        .section-header {
            position: relative;
            border-top: 2px solid #adadad;
            border-bottom: 2px solid #adadad;
        }

        .section-title {
            display: inline-block;
            padding: 10px 50px;
            font-weight: 600;
            font-size: 18px;
            color: #1F7D53;
            border-bottom: 3px solid #1F7D53;
            margin-bottom: -1px;
        }

        .custom-border {
            border-bottom: 2px solid #adadad;
        }

        .breadcrumb-item+.breadcrumb-item::before {
            display: none;
        }

        /* Feedback toast */
        #cartToast {
            position: fixed;
            bottom: 24px;
            right: 24px;
            z-index: 9999;
            min-width: 260px;
            display: none;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        #cartToast.show {
            display: block;
            opacity: 1;
        }

        /* Loading state pada tombol */
        .btn-loading {
            pointer-events: none;
            opacity: 0.7;
        }

        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .modal-box {
            background: white;
            padding: 20px;
            border-radius: 12px;
            text-align: center;
            width: 300px;
        }

        .modal-img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            margin: 10px 0;
        }

        .btn-cart {
            margin-top: 10px;
            padding: 10px 15px;
            background: #28a745;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }
    </style>

    {{-- DESKRIPSI PRODUK --}}
    <div class="row g-4 py-5">

        {{-- HEADER --}}
        <div class="d-flex align-items-center gap-5 mb-3">
            <h5 class="fw-bold mb-0 title-product">Deskripsi Produk</h5>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb small mb-0 align-items-center">
                    <li class="breadcrumb-item">
                        <a href="{{ route('home') }}" class="text-decoration-none text-muted">Home</a>
                    </li>
                    <li class="breadcrumb-item d-flex align-items-center active text-dark fw-semibold" aria-current="page">
                        <span class="iconify mx-2 text-muted" data-icon="solar:alt-arrow-right-linear"></span>
                        {{ $product->name }}
                    </li>
                </ol>
            </nav>
        </div>

        {{-- GAMBAR --}}
        <div class="col-lg-4">
            <div class="text-center">
                <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('images/home/category/beras-putih.png') }}"
                    class="img-fluid" alt="{{ $product->name }}">
            </div>
        </div>

        {{-- DETAIL PRODUK --}}
        <div class="col-lg-5">
            <div class="text-warning small mb-1 fs-3">★★★★☆</div>

            <h5 class="fw-semibold mb-2">{{ $product->name }}<br></h5>

            <div class="d-flex gap-4 small mb-2">
                <span class="text-muted">
                    Stok:
                    <span class="text-success fw-medium">{{ $product->stock > 0 ? 'Tersedia' : 'Habis' }}</span>
                </span>
                <span class="text-muted">
                    Kategori:
                    <span class="text-dark fw-medium">{{ $product->category->name ?? '-' }}</span>
                </span>
            </div>

            <p class="small text-muted mb-2">
                Terjual: <span class="text-dark fw-medium">{{ $product->sold ?? 0 }} Karung</span>
            </p>

            <h4 class="fw-bold price-product pb-2">
                Rp. {{ number_format($product->price, 0, ',', '.') }}
            </h4>

            <div class="section-header mb-3">
                <span class="section-title">Detail Produk</span>
            </div>

            <div class="small mb-2">
                <div class="d-flex mb-1">
                    <div class="text-muted" style="width: 130px;">Berat Per Karung</div>
                    <div class="text-muted" style="width: 10px;">:</div>
                    <div class="text-dark fw-medium">{{ $product->weight_kg ?? '1' }} Kg</div>
                </div>
            </div>

            {{-- CATEGORY NAME --}}
            <div class="border-bottom pb-2 mb-2" style="border-bottom: 2px solid #ddd !important;">
                <p class="small text-dark fw-semibold mb-0">{{ $product->category->name ?? 'Tidak ada kategori' }}</p>
                <p class="small text-dark fw-medium mb-0">
                    {{ $product->category->description ?? 'Tidak ada deskripsi kategori' }}</p>
            </div>

            <div class="border-bottom pb-2 mb-2" style="border-bottom: 2px solid #ddd !important;">
                <p class="small text-dark fw-bold mb-0">{{ $product->name ?? 'Tidak ada nama produk' }}</p>
                <p class="small text-dark fw-medium mb-0">{{ $product->description ?? 'Tidak ada deskripsi produk' }}</p>
            </div>
        </div>

        {{-- ATUR JUMLAH --}}
        <div class="col-lg-3">
            <div class="rounded p-3 bg-white">
                <h6 class="fw-bold mb-3">Atur Jumlah Dan Catatan</h6>

                <!-- JUMLAH + STOK -->
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="input-group input-group-sm qty-group">
                        <button class="btn qty-btn fw-bold" data-action="minus">-</button>
                        <input type="text" class="form-control text-center fw-semibold qty-input" value="1"
                            min="1" max="{{ $product->stock }}" data-price="{{ $product->price }}"
                            data-stock="{{ $product->stock }}">
                        <button class="btn qty-btn fw-bold" data-action="plus">+</button>
                    </div>
                </div>

                <!-- SUBTOTAL -->
                <div class="d-flex justify-content-between fw-bold mb-3">
                    <span class="fw-medium">Subtotal</span>
                    <span id="subtotal">Rp. {{ number_format($product->price, 0, ',', '.') }}</span>
                </div>

                <!-- Tombol Keranjang -->
                <button class="btn btn-main w-100 mb-2" id="btnKeranjang" data-product-id="{{ $product->id }}"
                    @if ($product->stock <= 0) disabled @endif>
                    <span class="btn-text">+ Keranjang</span>
                    <span class="btn-spinner d-none">
                        <span class="spinner-border spinner-border-sm me-1"></span>
                        Menambahkan...
                    </span>
                </button>

                <!-- Tombol Beli Langsung -->
                <button class="btn btn-second w-100" id="btnBeliLangsung" data-product-id="{{ $product->id }}"
                    @if ($product->stock <= 0) disabled @endif>
                    <span class="btn-text">Beli Langsung</span>
                    <span class="btn-spinner d-none">
                        <span class="spinner-border spinner-border-sm me-1"></span>
                        Memproses...
                    </span>
                </button>
            </div>
        </div>

        {{-- ULASAN --}}
        <div class="mt-5">
            <h6 class="fw-bold text-custom-green mb-3">Ulasan Pembeli</h6>
            <div class="border rounded p-4 text-muted text-center">Belum ada ulasan</div>
        </div>

        {{-- PILIHAN LAINNYA --}}
        <div class="mt-5">
            <h6 class="fw-bold text-custom-green mb-3">Pilihan Lainnya</h6>

            <div class="row g-3 produk-row">
                @forelse ($products as $product)
                    <div class="produk-col">
                        <a href="{{ route('detail-product', $product->slug) }}" class="text-decoration-none text-dark">
                            <div class="produk-card rounded">
                                <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('images/home/category/beras-putih.png') }}"
                                    class="img-fluid" alt="{{ $product->name }}">
                                <div class="produk-body">
                                    <div class="rating mb-3">★★★★★</div>
                                    <p class="produk-title mb-3">
                                        {{ $product->weight ?? '1 Liter' }} {{ $product->name }}<br>
                                    </p>
                                    <div class="produk-footer">
                                        <span class="harga">Rp. {{ number_format($product->price, 0, ',', '.') }}</span>
                                        <span class="terjual">Tersedia {{ $product->stock ?? 0 }}</span>
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
        </div>

    </div>

    <div class="modal fade" id="addToCartModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-center p-3">

                <div class="modal-header border-0">
                    <h5 class="modal-title w-100">Berhasil ditambahkan ke keranjang</h5>
                </div>

                <div class="modal-body">
                    <img id="modalProductImage" src="" class="img-fluid rounded mb-3"
                        style="max-height:120px; object-fit:cover;">

                    <p id="modalProductName" class="fw-semibold mb-1"></p>
                    <p id="modalProductQty" class="text-muted"></p>
                </div>

                <div class="modal-footer border-0 justify-content-center">
                    <a href="{{ route('cart') }}" class="btn btn-success">
                        Lihat Keranjang
                    </a>
                </div>

            </div>
        </div>
    </div>
    @include('frontend.components.guest-login')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // =============================================
            //  QTY LOGIC
            // =============================================
            const productData = {
                id: "{{ $product->id }}",
                name: "{{ $product->name }}",
                image: "{{ $product->image ? asset('storage/' . $product->image) : asset('images/home/category/beras-putih.png') }}"
            };
            const input = document.querySelector('.qty-input');
            const subtotalEl = document.getElementById('subtotal');
            const price = parseInt(input.dataset.price || 0);
            const stock = parseInt(input.dataset.stock || 0);

            function updateSubtotal() {
                const qty = parseInt(input.value) || 1;
                const total = qty * price;
                subtotalEl.innerText = 'Rp. ' + total.toLocaleString('id-ID');
            }

            document.querySelectorAll('.qty-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    let qty = parseInt(input.value) || 1;
                    if (btn.dataset.action === 'plus') {
                        if (stock > 0 && qty < stock) qty++;
                    } else if (btn.dataset.action === 'minus') {
                        if (qty > 1) qty--;
                    }
                    input.value = qty;
                    updateSubtotal();
                });
            });

            input.addEventListener('change', function() {
                let qty = parseInt(this.value) || 1;
                if (qty < 1) qty = 1;
                if (stock > 0 && qty > stock) qty = stock;
                this.value = qty;
                updateSubtotal();
            });

            updateSubtotal();

            // =============================================
            //  HELPERS
            // =============================================

            function getQty() {
                return parseInt(input.value) || 1;
            }

            async function apiFetch(url, options = {}) {
                const token = localStorage.getItem('token');

                if (!token) {
                    throw new Error('UNAUTHENTICATED');
                }

                const defaults = {
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${token}`,
                    },
                };

                const res = await fetch(url, {
                    ...defaults,
                    ...options,
                    headers: {
                        ...defaults.headers,
                        ...(options.headers ?? {})
                    }
                });

                // kalau token expired / invalid
                if (res.status === 401) {
                    localStorage.removeItem('token');
                    showGuestLoginModal(); // atau redirect login
                    throw new Error('UNAUTHORIZED');
                }

                const data = await res.json().catch(() => ({}));

                if (!res.ok) {
                    throw new Error(data.message ?? `HTTP ${res.status}`);
                }

                return data;
            }

            function setLoading(btn, loading) {
                const text = btn.querySelector('.btn-text');
                const spinner = btn.querySelector('.btn-spinner');
                if (loading) {
                    btn.classList.add('btn-loading');
                    text.classList.add('d-none');
                    spinner.classList.remove('d-none');
                } else {
                    btn.classList.remove('btn-loading');
                    text.classList.remove('d-none');
                    spinner.classList.add('d-none');
                }
            }

            let toastTimer;

            function showToast(msg, isError = false) {
                const toast = document.getElementById('cartToast');
                const inner = document.getElementById('cartToastInner');
                const msgEl = document.getElementById('cartToastMsg');

                if (!toast || !inner || !msgEl) return; // ✅ penting

                const icon = inner.querySelector('i');

                msgEl.textContent = msg;

                if (icon) {
                    icon.className = isError ?
                        'bi bi-x-circle-fill text-danger fs-5' :
                        'bi bi-check-circle-fill text-success fs-5';
                }

                toast.style.display = 'block';
                toast.offsetHeight;
                toast.classList.add('show');

                setTimeout(() => {
                    toast.classList.remove('show');
                    setTimeout(() => toast.style.display = 'none', 300);
                }, 2500);
            }

            // =============================================
            //  + KERANJANG
            // =============================================

            document.getElementById('btnKeranjang').addEventListener('click', async function() {
                const btn = this;

                const token = localStorage.getItem('token');

                if (!token) {
                    showGuestLoginModal();
                    return;
                }

                const productId = btn.dataset.productId;
                const qty = getQty();

                setLoading(btn, true);

                try {
                    await apiFetch('/api/cart/items', {
                        method: 'POST',
                        body: JSON.stringify({
                            product_id: productId,
                            quantity: qty
                        }),
                    });

                    showAddToCartModal(productData, qty);

                } catch (e) {
                    showToast(e.message || 'Gagal', true);
                } finally {
                    setLoading(btn, false);
                }
            });

            // =============================================
            //  BELI LANGSUNG (tambah ke cart lalu redirect)
            // =============================================

            document.getElementById('btnBeliLangsung').addEventListener('click', async function() {
                const btn = this;
                const productId = btn.dataset.productId;
                const qty = getQty();

                setLoading(btn, true);
                try {
                    await apiFetch('/api/cart/items', {
                        method: 'POST',
                        body: JSON.stringify({
                            product_id: productId,
                            quantity: qty
                        }),
                    });
                    // Langsung ke halaman keranjang
                    window.location.href = '{{ route('checkout') }}';
                } catch (e) {
                    showToast(e.message || 'Gagal memproses', true);
                    setLoading(btn, false);
                }
            });

            function showAddToCartModal(product, qty) {
                const image = document.getElementById('modalProductImage');
                const name = document.getElementById('modalProductName');
                const qtyText = document.getElementById('modalProductQty');

                image.src = product.image;
                name.textContent = product.name;

                const modal = new bootstrap.Modal(document.getElementById('addToCartModal'));
                modal.show();
                setTimeout(() => {
                    modal.hide();
                }, 3000);
            }

            function addToCart(id, name, image) {
                const product = {
                    id: id,
                    name: name,
                    image: image
                };

                const qty = 1;

                showAddToCartModal(product, qty);
            }
        });


        function showGuestLoginModal() {
            const modalEl = document.getElementById('loginRequiredModal');

            if (!modalEl) return;

            setTimeout(() => {
                bootstrap.Modal.getOrCreateInstance(modalEl).show();
            }, 10);
        }
    </script>
@endsection
