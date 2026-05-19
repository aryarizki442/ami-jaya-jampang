@extends('app')

@section('title', 'Keranjang Belanja')

@section('content')

    <style>
        /* =======================
                                                                         TYPOGRAPHY
                                                                        ======================= */
        .title-cart {
            font-size: 28px;
        }

        /* =======================
                                                                           CART ITEM & CARD
                                                                        ======================= */
        .cart-item,
        .card {
            background-color: #fff;
            border: none;
            box-shadow: none;
            transition: background 0.2s;
        }

        .cart-item:hover {
            background: #F6F6F6;
        }

        .custom-border {
            border-bottom: 1px solid #000000;
        }

        /* =======================
                                                                        FORM CHECKBOX
                                                                        ======================= */
        .form-check {
            background-color: #fff;
            border: 1px solid #F6F6F6;
            border-radius: 5px;
            padding: 20px 47px;
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
        }

        .form-check-input:focus {
            outline: none;
            box-shadow: none;
        }

        .form-check-input:checked {
            background-color: #1F7D53;
            border-color: #1F7D53;
            accent-color: #1F7D53;
        }

        .form-check-input:checked::after {
            background-color: #fff;
            border-color: #1F7D53;
        }

        /* =======================
                                                                         QUANTITY CONTROL
                                                                        ======================= */
        .qty-group {
            border: 1px solid #adadad;
            border-radius: 5px;
            display: flex;
            align-items: center;
            overflow: hidden;
            width: 100%;
            max-width: 100px;
            height: 32px;
        }

        .qty-group .qty-btn {
            background-color: #fff;
            border: none;
            color: #555;
            height: 100%;
            padding: 0 6px;
            font-size: 16px;
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
            font-size: 14px;
            padding: 0;
            flex-grow: 1;
        }

        /* =======================
                                                                                                                                                                                                               CART CONTAINER
                                                                                                                                                                                                            ======================= */
        .cart-scroll {
            max-height: 650px;
            overflow-y: auto;
            padding-right: 5px;
        }

        .cart-empty {
            text-align: center;
            padding: 40px 20px;
            color: #aaa;
        }

        /* =======================
                                                                                                                                                                                                               SKELETON LOADING
                                                                                                                                                                                                            ======================= */
        .skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: shimmer 1.2s infinite;
            border-radius: 5px;
        }

        @keyframes shimmer {
            0% {
                background-position: 200% 0;
            }

            100% {
                background-position: -200% 0;
            }
        }

        /* =======================
                                                                                                                                                                                                               TOAST NOTIFICATION
                                                                                                                                                                                                            ======================= */
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

        /* =======================
                                                                                                                                                                                                               RESPONSIVE
                                                                                                                                                                                                            ======================= */
        @media (max-width: 576px) {
            .qty-group {
                max-width: 80px;
                height: 28px;
            }

            .qty-group .qty-btn {
                font-size: 14px;
                padding: 0 4px;
            }

            .qty-group .form-control {
                font-size: 12px;
            }
        }
    </style>

    <section>
        <h5 class="fw-bold mt-0 mb-0  title-cart py-4">Keranjang</h5>

        <div class="row d-flex align-items-start g-4">
            <!-- List Keranjang -->
            <div class="col-md-8">
                <div class="form-check mb-2">
                    <label class="form-check-label" for="selectAll">
                        <input class="form-check-input" type="checkbox" id="selectAll">
                        Pilih Semua
                    </label>
                </div>
                <div class="cart-scroll px-0" id="cartList">
                    <div id="cartSkeleton">
                        @for ($i = 0; $i < 3; $i++)
                            <div class="card mb-2 p-4 d-flex flex-row align-items-center cart-item">
                                <div class="skeleton me-3" style="width:20px;height:20px;border-radius:3px;flex-shrink:0">
                                </div>
                                <div class="skeleton rounded-circle me-3" width="100" height="100"></div>
                                <div class="skeleton rounded-circle me-3" width="100" height="100"
                                    style="width:100px;height:100px;flex-shrink:0"></div>
                                <div class="flex-grow-1">
                                    <div class="skeleton mb-2" style="width:60%;height:16px"></div>
                                    <div class="skeleton" style="width:40%;height:14px"></div>
                                </div>
                                <div style="width:120px">
                                    <div class="skeleton mb-3" style="height:20px"></div>
                                    <div class="skeleton" style="height:32px"></div>
                                </div>
                            </div>
                        @endfor
                    </div>
                    <div id="cartItems"></div>
                    <div id="cartEmpty" class="cart-empty d-none">
                        <i class="bi bi-cart-x" style="font-size:48px"></i>
                        <p class="mt-2">Keranjang kamu kosong</p>
                    </div>
                </div>
            </div>

            <!-- Ringkasan Belanja -->
            <div class="col-md-4 d-flex">
                <div class="card p-4 w-100">
                    <h6>Ringkasan Belanja</h6>
                    <div class="d-flex justify-content-between align-items-center custom-border pb-4 mb-3 mt-3">
                        <span class="text-muted">Total</span>
                        <span class="fw-bold" id="totalAmount">Rp.0</span>
                    </div>
                    <button class="btn btn-custom-green w-100" id="btnBeliKeranjang">Beli</button>
                </div>
            </div>

            {{-- Rekomendasi Untukmu --}}
            <div class="mt-5 mb-5">
                <h6 class="fw-bold text-custom-green mb-3">Rekomendasi Untukmu</h6>
                <div class="row g-3 produk-row">
                    @forelse ($recommendedProducts as $product)
                        <div class="best-col">

                            <div class="best-card rounded h-100 d-flex flex-column">

                                <a href="{{ route('detail-product', $product->slug) }}"
                                    class="text-decoration-none text-dark d-block flex-grow-1">

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

                                </a>

                                {{-- 🔥 BUTTON KERANJANG --}}
                                <div class="p-2">
                                    <button class="btn btn-second w-100 btn-rekomendasi-cart"
                                        data-product-id="{{ $product->id }}" data-product-name="{{ $product->name }}"
                                        data-product-image="{{ $product->image ? asset('storage/' . $product->image) : asset('images/home/category/beras-putih.png') }}">
                                        + Keranjang
                                    </button>
                                </div>

                            </div>

                        </div>
                    @empty
                        <p class="text-muted">Belum ada produk rekomendasi</p>
                    @endforelse
                </div>
            </div>
        </div>
    </section>

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

            </div>
        </div>
    </div>

    <script>
        const API_BASE = '/api';

        // =============================================
        //  HELPERS
        // =============================================

        async function apiFetch(url, options = {}) {
            const token = localStorage.getItem('token');
            if (!token) {
                window.location.href = "{{ route('login') }}";
                return;
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
            if (res.status === 401) {
                localStorage.removeItem('token');
                window.location.href = "{{ route('login') }}";
                return;
            }
            if (!res.ok) {
                const err = await res.json().catch(() => ({}));
                throw new Error(err.message ?? `HTTP ${res.status}`);
            }
            return res.json();
        }

        function formatRupiah(amount) {
            return 'Rp.' + Number(amount).toLocaleString('id-ID');
        }

        let toastTimer;

        function showToast(msg, isError = false) {
            const toast = document.getElementById('cartToast');
            const msgEl = document.getElementById('cartToastMsg');
            const iconEl = document.getElementById('cartToastIcon');
            msgEl.textContent = msg;
            iconEl.className = isError ?
                'bi bi-x-circle-fill text-danger fs-5' :
                'bi bi-check-circle-fill text-success fs-5';
            toast.style.display = 'block';
            toast.offsetHeight;
            toast.classList.add('show');
            clearTimeout(toastTimer);
            toastTimer = setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => {
                    toast.style.display = 'none';
                }, 300);
            }, 2500);
        }

        // =============================================
        //  RENDER CART ITEM
        // =============================================

        function createCartItemEl(item) {
            const div = document.createElement('div');
            div.className = 'card mb-2 p-4 d-flex flex-row align-items-center cart-item';
            div.dataset.itemId = item.id;
            div.dataset.price = item.price;
            div.dataset.productId = item.product_id;

            const imgSrc = item.image ?? "{{ asset('images/home/category/beras-putih.png') }}";

            div.innerHTML = `
                <input class="form-check-input me-3 item-checkbox" type="checkbox" ${item.is_selected ? 'checked' : ''}>
              <img src="${imgSrc}"
     alt="${item.product_name}"
     class=" me-3"
     width="100"
     height="100"
     style="object-fit: cover; border-radius: 8px;"
     onerror="this.src='{{ asset('images/home/category/beras-putih.png') }}'">
                <div class="flex-grow-1">
                    <p class="mb-0">${item.product_name}</p>
                </div>
                <div class="text-end">
                    <h5 class="mb-4 fw-semibold item-price">${formatRupiah(item.price)}</h5>
                    <div class="d-flex align-items-center justify-content-end">
                        <button class="btn btn-lg me-2 btn-delete">
                            <i class="bi bi-trash"></i>
                        </button>
                        <div class="input-group input-group-sm qty-group">
                            <button class="btn qty-btn fw-bold" data-action="minus">-</button>
                            <input type="text"
                                   class="form-control text-center fw-semibold qty-input"
                                   value="${item.quantity}">
                            <button class="btn qty-btn fw-bold" data-action="plus">+</button>
                        </div>
                    </div>
                </div>`;

            div.querySelector('.item-checkbox').addEventListener('change', function() {
                updateItemSelected(item.id, this.checked, div);
            });

            div.querySelectorAll('.qty-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const input = div.querySelector('.qty-input');
                    let qty = parseInt(input.value) || 1;
                    if (this.dataset.action === 'plus') qty++;
                    else if (this.dataset.action === 'minus' && qty > 1) qty--;
                    input.value = qty;
                    updateItemQty(item.id, qty, div);
                });
            });

            div.querySelector('.qty-input').addEventListener('change', function() {
                let qty = parseInt(this.value) || 1;
                if (qty < 1) qty = 1;
                this.value = qty;
                updateItemQty(item.id, qty, div);
            });

            div.querySelector('.btn-delete').addEventListener('click', function() {
                removeItem(item.id, div);
            });

            return div;
        }

        // =============================================
        //  LOAD CART
        // =============================================

        async function loadCart() {
            try {
                const res = await apiFetch(`${API_BASE}/cart`);

                const container = document.getElementById('cartItems');
                const items = res.data.items ?? [];

                document.getElementById('cartSkeleton').classList.add('d-none');

                container.innerHTML = '';

                if (!items.length) {
                    document.getElementById('cartEmpty').classList.remove('d-none');
                    updateTotalDisplay(0);
                    return;
                }

                document.getElementById('cartEmpty').classList.add('d-none');

                // 🔥 RENDER (PAKAI PREPEND kalau mau terbaru di atas)
                items.forEach(item => {
                    container.prepend(createCartItemEl(item));
                });

                updateTotalDisplay(res.data.summary.selected_total ?? 0);
                syncSelectAllCheckbox();

            } catch (e) {
                document.getElementById('cartSkeleton').classList.add('d-none');
                console.error('Gagal load cart:', e);
            }
        }

        // =============================================
        //  UPDATE QTY
        // =============================================

        const qtyTimers = {};

        function updateItemQty(itemId, quantity, itemEl) {
            clearTimeout(qtyTimers[itemId]);
            qtyTimers[itemId] = setTimeout(async () => {
                try {
                    await apiFetch(`${API_BASE}/cart/items/${itemId}`, {
                        method: 'PUT',
                        body: JSON.stringify({
                            quantity
                        }),
                    });
                    recalcTotalFromDOM();
                } catch (e) {
                    showToast(e.message || 'Gagal update qty', true);
                    loadCart();
                }
            }, 400);
        }

        // =============================================
        //  UPDATE SELECTED
        // =============================================

        async function updateItemSelected(itemId, isSelected, itemEl) {
            try {
                await apiFetch(`${API_BASE}/cart/items/${itemId}`, {
                    method: 'PUT',
                    body: JSON.stringify({
                        is_selected: isSelected
                    }),
                });
                recalcTotalFromDOM();
                syncSelectAllCheckbox();
            } catch (e) {
                console.error('Gagal update selected:', e);
            }
        }

        // =============================================
        //  REMOVE ITEM
        // =============================================

        async function removeItem(itemId, itemEl) {
            try {
                await apiFetch(`${API_BASE}/cart/items/${itemId}`, {
                    method: 'DELETE'
                });
                itemEl.remove();
                recalcTotalFromDOM();
                syncSelectAllCheckbox();
                if (document.querySelectorAll('#cartItems .cart-item').length === 0) {
                    document.getElementById('cartEmpty').classList.remove('d-none');
                }
            } catch (e) {
                showToast(e.message || 'Gagal hapus item', true);
            }
        }

        // =============================================
        //  SELECT ALL
        // =============================================

        document.getElementById('selectAll').addEventListener('change', async function() {
            const selected = this.checked;
            try {
                await apiFetch(`${API_BASE}/cart/select-all`, {
                    method: 'POST',
                    body: JSON.stringify({
                        selected
                    }),
                });
                document.querySelectorAll('#cartItems .item-checkbox').forEach(cb => {
                    cb.checked = selected;
                });
                recalcTotalFromDOM();
            } catch (e) {
                console.error('Gagal select all:', e);
            }
        });

        function syncSelectAllCheckbox() {
            const all = document.querySelectorAll('#cartItems .item-checkbox');
            const checked = document.querySelectorAll('#cartItems .item-checkbox:checked');
            const selectAll = document.getElementById('selectAll');
            if (all.length === 0) {
                selectAll.checked = false;
                selectAll.indeterminate = false;
            } else if (checked.length === all.length) {
                selectAll.checked = true;
                selectAll.indeterminate = false;
            } else if (checked.length === 0) {
                selectAll.checked = false;
                selectAll.indeterminate = false;
            } else {
                selectAll.checked = false;
                selectAll.indeterminate = true;
            }
        }

        // =============================================
        //  TOTAL
        // =============================================

        function recalcTotalFromDOM() {
            let total = 0;
            document.querySelectorAll('#cartItems .cart-item').forEach(item => {
                if (!item.querySelector('.item-checkbox').checked) return;
                const price = parseFloat(item.dataset.price) || 0;
                const qty = parseInt(item.querySelector('.qty-input').value) || 0;
                total += price * qty;
            });
            updateTotalDisplay(total);
        }

        function updateTotalDisplay(amount) {
            document.getElementById('totalAmount').textContent = formatRupiah(amount);
        }

        function showAddToCartModal(product, qty = 1) {
            const image = document.getElementById('modalProductImage');
            const name = document.getElementById('modalProductName');
            const qtyText = document.getElementById('modalProductQty');

            image.src = product.image;
            name.textContent = product.name;

            const modalEl = document.getElementById('addToCartModal');
            const modal = new bootstrap.Modal(modalEl);

            modal.show();

            setTimeout(() => {
                modal.hide();
            }, 3000);
        }



        // =============================================
        //  REKOMENDASI — TOMBOL + KERANJANG
        // =============================================

        async function addToCart(productId, btn) {
            if (!productId) return;

            const originalText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = `<span class="spinner-border spinner-border-sm me-1"></span> Menambahkan...`;

            try {
                await apiFetch(`${API_BASE}/cart/items`, {
                    method: 'POST',
                    body: JSON.stringify({
                        product_id: productId,
                        quantity: 1
                    }),
                });

                const product = {
                    id: productId,
                    name: btn.dataset.productName,
                    image: btn.dataset.productImage
                };

                showAddToCartModal(product, 1);

                loadCart();

            } catch (e) {
                console.error(e);

                showAddToCartModal({
                    name: 'Gagal menambahkan produk',
                    image: '/images/error.png'
                }, 0);

            } finally {
                btn.disabled = false;
                btn.innerHTML = originalText;
            }
        }

        document.querySelectorAll('.btn-rekomendasi-cart').forEach(btn => {
            btn.addEventListener('click', function() {
                addToCart(this.dataset.productId, this);
            });
        });
        loadCart();


        // =============================================
        //  BELI LANGSUNG
        // =============================================
        document.getElementById('btnBeliKeranjang').addEventListener('click', function() {

            const selectedItems = Array.from(
                document.querySelectorAll('#cartItems .item-checkbox:checked')
            ).map(cb => parseInt(cb.closest('.cart-item').dataset.itemId));

            console.log('selectedItems:', selectedItems); // 👈 cek di F12 console
            console.log('redirect to:', '/checkout?items=' + JSON.stringify(selectedItems)); // 👈

            if (selectedItems.length === 0) {
                alert('Pilih produk terlebih dahulu');
                return;
            }

            window.location.href = '/checkout?items=' + JSON.stringify(selectedItems);
        });


        // =============================================
        //  INIT
        // =============================================
    </script>
@endsection
