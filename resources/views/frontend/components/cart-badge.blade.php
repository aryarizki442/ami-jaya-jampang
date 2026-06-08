<style>
    .cart-wrap {
        cursor: pointer;
        position: relative;
        display: flex;
        align-items: center;
        margin-right: 50px;
    }

    .cart-badge {
        position: absolute;
        top: -8px;
        right: -10px;
        background: red;
        color: white;
        font-size: 11px;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
    }

    .cart-dropdown {
        position: absolute;
        top: 48px;
        right: 0;

        width: 420px;

        background: white;
        border-radius: 12px;

        z-index: 99999;

        display: none;

        overflow: visible;
    }

    /* ARROW */
    .cart-dropdown::before {
        content: "";

        position: absolute;

        top: -8px;
        right: 50px;

        width: 18px;
        height: 18px;

        background: white;

        transform: rotate(45deg);

        border-radius: 3px;
    }

    .cart-dropdown.show {
        display: block;
    }

    .cart-header {
        padding: 14px 18px;
        border-bottom: 1px solid #eee;
    }

    .cart-list {
        max-height: 420px;
        overflow-y: auto;
    }

    .cart-item {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 14px 18px;
        transition: .2s;
    }

    .cart-item:hover {
        background: #f8f8f8;
    }

    .cart-image {
        width: 52px;
        height: 52px;
        object-fit: cover;
        border-radius: 6px;
        flex-shrink: 0;
    }

    .cart-title {
        font-size: 15px;
        line-height: 1.3;

        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .cart-price {
        font-weight: 600;
        white-space: nowrap;
        font-size: 15px;
    }

    /* =========================
       RESPONSIVE
    ========================== */

    @media (max-width: 768px) {

        .cart-wrap {
            margin-right: 20px;
        }

        .cart-dropdown {
            position: fixed;
            top: 70px;
            left: 12px;
            right: 12px;
            width: auto;
            border-radius: 14px;
            z-index: 12;

        }

        .cart-dropdown::before {
            display: none;
        }

        .cart-list {
            max-height: 60vh;
        }

        .cart-item {
            gap: 12px;
            padding: 12px 14px;
        }

        .cart-image {
            width: 48px;
            height: 48px;
        }

        .cart-title {
            font-size: 14px;
        }

        .cart-price {
            font-size: 14px;
        }
    }

    @media (max-width: 480px) {

        .cart-header {
            padding: 12px 14px;
            z-index: 12;

        }

        .cart-item {
            align-items: flex-start;
        }

        .cart-price {
            font-size: 13px;
        }

        .cart-badge {
            width: 17px;
            height: 17px;
            font-size: 10px;
        }
    }
</style>
<!-- CART -->
<div class="position-relative">

    <a href="javascript:void(0)" class="text-white fs-5 cart-wrap" id="cartToggleBtn">

        <iconify-icon icon="tdesign:cart" width="20" height="20">
        </iconify-icon>

        <span class="cart-badge d-none" id="cartBadge">
            0
        </span>
    </a>

    <div class="cart-dropdown shadow" id="cartDropdown">

        <div class="cart-header d-flex justify-content-between align-items-center">

            <div>
                <span class="fw-semibold">Keranjang</span>
                <span class="text-secondary" id="cartCountText">(0)</span>
            </div>

            <a href="{{ route('cart') }}" class="text-success fw-semibold text-decoration-none">
                Lihat Semua
            </a>

        </div>

        <div class="cart-list" id="cartList">

            <div class="p-4 text-center text-secondary">
                Memuat...
            </div>

        </div>

    </div>

</div>
<script>
    const cartBtn = document.getElementById('cartToggleBtn');
    const cartDropdown = document.getElementById('cartDropdown');
    const cartList = document.getElementById('cartList');
    const cartBadge = document.getElementById('cartBadge');
    const cartCountText = document.getElementById('cartCountText');

    async function loadCartDropdown() {

        const token = localStorage.getItem('token');

        // belum login
        if (!token) {

            cartList.innerHTML = `
                <div class="p-4 text-center text-secondary">
                    Silakan login terlebih dahulu
                </div>
            `;

            cartBadge.classList.add('d-none');

            return;
        }

        try {

            const response = await fetch('/api/cart', {
                headers: {
                    Authorization: `Bearer ${token}`,
                    Accept: 'application/json'
                }
            });

            const result = await response.json();

            const items = result.data.items || [];

            const totalQty = items.length || 0;

            // badge
            cartBadge.innerText = totalQty;
            cartCountText.innerText = `(${totalQty})`;

            if (totalQty > 0) {
                cartBadge.classList.remove('d-none');
            }

            // kosong
            if (items.length === 0) {

                cartList.innerHTML = `
    <div class="p-4 text-center">
        <div
            class="d-inline-flex align-items-center justify-content-center mb-3"
            style="
                width: 90px;
                height: 90px;
                border-radius: 50%;
                background-color: #E9F2EE;
            "
        >
            <i
                class='bx bxs-cart'
                style="
                    font-size: 52px;
                    color: #198754;
                    line-height: 1;
                "
            ></i>
        </div>

        <div class="fw-semibold">
            Tidak ada produk di keranjang anda
        </div>
    </div>
`;

                return;
            }

            // render
            cartList.innerHTML = '';

            items.slice(0, 5).forEach(item => {

                cartList.innerHTML += `
        <a href="#"
            class="cart-item text-decoration-none text-dark">

            <img src="${item.image}"
                class="cart-image">

            <div class="flex-grow-1">
                <div class="cart-title">
                    ${item.product_name}
                </div>
            </div>

            <div class="cart-price">
                ${item.quantity} x
                Rp.${Number(item.price).toLocaleString('id-ID')}
            </div>

        </a>
    `;
            });

        } catch (error) {

            console.error(error);

            cartList.innerHTML = `
                <div class="p-4 text-center ">
                    Belum ada produk di keranjang anda
                </div>
            `;
        }
    }

    // toggle dropdown
    cartBtn.addEventListener('click', async function(e) {

        e.stopPropagation();

        cartDropdown.classList.toggle('show');

        if (cartDropdown.classList.contains('show')) {
            await loadCartDropdown();
        }
    });

    // close outside
    document.addEventListener('click', function(e) {

        if (
            !cartDropdown.contains(e.target) &&
            !cartBtn.contains(e.target)
        ) {
            cartDropdown.classList.remove('show');
        }
    });
    document.addEventListener('DOMContentLoaded', function() {
        loadCartDropdown();
    });
</script>
