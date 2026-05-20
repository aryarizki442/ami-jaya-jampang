@extends('backend.app')

@section('title', 'Produk')

@section('content')

    <style>
        #imagePreview {
            position: relative;
        }

        /* IMAGE BACKGROUND */
        #previewImg {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: 1;
            display: none;
        }

        /* EMPTY STATE */
        #uploadContent {
            position: absolute;
            inset: 0;
            z-index: 2;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* HOVER OVERLAY */
        .hover-overlay {
            position: absolute;
            inset: 0;
            z-index: 3;

            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;

            opacity: 0;
            transition: 0.2s ease;

            pointer-events: none;
        }

        /* HOVER ACTIVE */
        #imagePreview:hover .hover-overlay {
            opacity: 1;
        }
    </style>

    <form id="formProduk" enctype="multipart/form-data">

        <div class="row g-2 align-items-start">
            <!-- HEADER -->
            <div class="d-flex align-items-center mt-0">
                <a href="{{ route('admin.product.index') }}" class="d-flex align-items-center text-decoration-none text-dark">
                    <i class="ri-arrow-left-line fs-5 me-2"></i>
                    <span class="fw-medium">Kembali</span>
                </a>
            </div>

            <h5 class="fw-semibold">Edit Produk</h5>

            <!-- LEFT -->
            <div class="col-md-8 d-flex">
                <div class="card p-4 border-0 shadow-sm w-100 h-100">

                    <!-- NAMA -->
                    <h6 class="mb-2">Nama Produk</h6>
                    <input type="text" name="name" id="name" class="form-control mb-2"
                        placeholder="Masukan Nama Produk">
                    <small class="text-danger error-name"></small>

                    <!-- DESKRIPSI -->
                    <h6 class="mb-2 mt-3">Deskripsi Produk</h6>
                    <textarea name="description" id="description" class="form-control mb-2" placeholder="Masukan Deskripsi Kategori"
                        style="height:50px; resize:none;"></textarea>
                    <small class="text-danger error-description"></small>

                    <!-- HARGA -->
                    <h6 class="mb-2 mt-3 border-top pt-3">Harga Produk</h6>
                    <input type="number" name="price" id="price" class="form-control"
                        placeholder="Masukan Harga Produk">
                    <small class="text-danger error-price"></small>

                    <!-- ROW -->
                    <div class="row mt-3 border-top pt-3">
                        <div class="col-md-6 ">
                            <h6 class="mb-2 ">Stok</h6>
                            <input type="number" name="stock" id="stock" class="form-control"
                                placeholder="Masukan Stok Produk" min="0" step="1">
                        </div>

                        <div class="col-md-6">
                            <h6 class="mb-2">Berat Produk</h6>
                            <input type="number" name="weight_kg" id="weight_kg" class="form-control"
                                placeholder="Masukan Berat Produk Kg/Liter" min="0" step="0.01">
                        </div>
                    </div>
                    <div class="row mt-3 border-top pt-3">

                        <div class="col-md-3">
                            <select name="category_id" id="category_id" class="form-select text-muted">
                                <option value="">Pilih Kategori</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            <small class="text-danger error-category_id"></small>
                        </div>
                    </div>

                    <div class="form-check form-switch mt-4">
                        <input class="form-check-input" type="checkbox" id="isRecommended">
                        <label class="form-check-label" for="isRecommended">
                            Jadikan Rekomendasi
                        </label>
                    </div>

                    <!-- STATUS -->
                    <div class="form-check form-switch mt-4">
                        <input class="form-check-input" type="checkbox" id="isActive">
                        <label class="form-check-label">Tampilkan Produk</label>
                    </div>

                </div>
            </div>

            <!-- RIGHT -->
            <div class="col-md-4 d-flex">
                <div class="card p-4 border-0 shadow-sm w-100 h-100 d-flex flex-column">

                    <h6 class="mb-3">Gambar Produk</h6>
                    <div id="imagePreview" class="border rounded w-100 text-center"
                        style="min-height:220px; overflow:hidden; position:relative; cursor:pointer;">

                        <!-- IMAGE PREVIEW -->
                        <img id="previewImg" style="width:100%; height:220px; object-fit:contain; display:none;">

                        <!-- EMPTY STATE -->
                        <div id="uploadContent">
                            <i class="ri-image-line fs-2 text-muted"></i>
                        </div>

                        <!-- HOVER OVERLAY -->
                        <div class="hover-overlay">
                            <button type="button" class="btn btn-image-edit-admin fw-medium mb-2">
                                Masukan Gambar
                            </button>
                            <div class="small text-neutral-custom">Klik atau seret gambar</div>
                        </div>

                    </div>
                    <input type="file" name="image" id="imageInput" class="d-none" accept="image/*">

                    <small class="text-danger error-image mt-2 d-block text-center"></small>

                </div>
            </div>

        </div>

        <!-- BUTTON -->
        <div class="mt-3 border-top pt-3 d-flex justify-content-end gap-2">
            <a href="/admin/product" class="btn btn-second">Batal</a>
            <button type="submit" id="btnSubmit" class="btn btn-main">Simpan</button>
        </div>
    </form>


    {{-- MODAL EROR --}}
    <div class="modal fade" id="errorModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-center p-4">
                <h5 class="mb-2 text-danger">Gagal</h5>
                <p id="errorMessage" class="mb-0"></p>
            </div>
        </div>
    </div>


    <script>
        document.addEventListener("DOMContentLoaded", function() {

            const form = document.getElementById('formProduk');
            const input = document.getElementById('imageInput');
            const dropArea = document.getElementById('imagePreview');
            const uploadContent = document.getElementById('uploadContent');
            const btnSubmit = document.getElementById('btnSubmit');

            const productId = @json($product->id ?? null);
            const token = localStorage.getItem('token');

            if (!form || !input || !dropArea) return;


            if (dropArea && input) {
                dropArea.addEventListener('click', () => {
                    input.click();
                });
            }

            // =========================
            // SET VALUE HELPER
            // =========================
            function setValue(id, value) {
                const el = document.getElementById(id);
                if (el) el.value = value ?? '';
            }

            // =========================
            // DRAG BLOCK
            // =========================
            function initDragBlock() {
                ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(event => {
                    document.body.addEventListener(event, function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                    });
                });
            }

            // =========================
            // RENDER IMAGE PREVIEW
            // =========================
            function renderImage(url) {
                const img = document.getElementById('previewImg');
                const empty = document.getElementById('uploadContent');

                if (img) {
                    img.src = url;
                    img.style.display = 'block';
                }

                if (empty) {
                    empty.style.display = 'none';
                }
            }

            // =========================
            // LOAD PRODUCT
            // =========================
            async function loadProduct() {
                if (!productId) return;

                try {
                    const res = await fetch(`/api/admin/products/${productId}`, {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'Authorization': 'Bearer ' + token
                        }
                    });

                    const result = await res.json();
                    if (!res.ok || !result?.success) return;

                    const p = result.data;

                    setValue('name', p.name);
                    setValue('description', p.description);
                    setValue('price', p.price);
                    setValue('stock', p.stock);
                    setValue('unit', p.unit);
                    setValue('weight_kg', p.weight_kg);
                    setValue('min_order', p.min_order);
                    setValue('max_order', p.max_order);

                    const categoryEl = document.getElementById('category_id');

                    if (categoryEl) {
                        const value = String(
                            p.category_id ?? p.category?.id ?? ''
                        );

                        console.log('SET CATEGORY VALUE:', value);

                        categoryEl.value = value;

                        if (categoryEl.value !== value) {
                            Array.from(categoryEl.options).forEach(opt => {
                                opt.selected = String(opt.value) === value;
                            });
                        }
                    }
                    const activeEl = document.getElementById('isActive');
                    if (activeEl) {
                        activeEl.checked = Number(p.is_active) === 1;
                    }

                    const recommendedEl = document.getElementById('isRecommended');
                    if (recommendedEl) {
                        recommendedEl.checked = Number(p.is_recommended) === 1;
                    }

                    if (p.image) {
                        renderImage(
                            p.image.startsWith('http') ?
                            p.image :
                            `/storage/${p.image.replace(/^\/+/, '')}`
                        );
                    }


                } catch (err) {
                    console.error('Error load product:', err);
                }
            }

            // =========================
            // PREVIEW IMAGE BARU
            // =========================
            input.addEventListener('change', function() {
                const file = this.files[0];
                if (!file) return;

                renderImage(URL.createObjectURL(file));
            });

            // =========================
            // UPDATE DATA (API)
            // =========================
            form.addEventListener('submit', async function(e) {
                e.preventDefault();

                document.querySelectorAll('.text-danger')
                    .forEach(el => el.innerText = '');

                const formData = new FormData();

                // ambil semua input text manual (biar kontrol jelas)
                formData.append('name', document.getElementById('name').value);
                formData.append('description', document.getElementById('description').value);
                formData.append('price', document.getElementById('price').value);
                formData.append('stock', document.getElementById('stock').value);
                formData.append('weight_kg', document.getElementById('weight_kg').value);
                formData.append('category_id', document.getElementById('category_id').value);

                formData.append(
                    'is_active',
                    document.getElementById('isActive').checked ? 1 : 0
                );
                formData.append(
                    'is_recommended',
                    document.getElementById('isRecommended').checked ? 1 : 0
                );

                formData.append('_method', 'POST');

                const file = document.getElementById('imageInput').files[0];

                if (file) {
                    formData.delete('image');
                    formData.append('image', file);
                    formData.append('replace_image', 1);
                }

                btnSubmit.disabled = true;
                btnSubmit.innerText = 'Loading...';

                try {
                    const res = await fetch(`/api/admin/products/${productId}`, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'Accept': 'application/json',
                            'Authorization': 'Bearer ' + token
                        }
                    });

                    const result = await res.json();

                    if (res.status === 422) {
                        showErrorModal(result.message ?? 'Validation error');
                        return;
                    }

                    if (result.success) {
                        window.location.href =
                            `/admin/product/index?updated=1&id=${productId}`;
                    }

                } catch (err) {
                    console.error(err);
                    showErrorModal('Terjadi kesalahan server');
                } finally {
                    btnSubmit.disabled = false;
                    btnSubmit.innerText = 'Update';
                }
            });

            function showErrorModal(message) {
                document.getElementById('errorMessage').innerText = message;
                new bootstrap.Modal(document.getElementById('errorModal')).show();
            }

            // =========================
            // INIT
            // =========================
            initDragBlock();
            loadProduct();

        });
    </script>
@endsection