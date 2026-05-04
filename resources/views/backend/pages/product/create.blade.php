@extends('backend.app')

@section('title', 'Produk')

@section('content')

    <style>
        #dropArea.border-primary {
            background: #f8f9fa;
            border: 2px dashed #0d6efd !important;
        }
    </style>

    <form id="formProduk" enctype="multipart/form-data">

        <div class="row g-2 align-items-start">
            <!-- HEADER -->
            <div class="d-flex align-items-center mt-0">
                <a href="{{ route('admin.product') }}" class="d-flex align-items-center text-decoration-none text-dark">
                    <i class="ri-arrow-left-line fs-5 me-2"></i>
                    <span class="fw-medium">Kembali</span>
                </a>
            </div>

            <h5 class="fw-semibold">Tambah Produk</h5>

            <!-- LEFT -->
            <div class="col-md-8 d-flex">
                <div class="card p-4 border-0 shadow-sm w-100 h-100">

                    <!-- NAMA -->
                    <h6 class="mb-2">Nama Produk</h6>
                    <input type="text" name="name" class="form-control mb-2" placeholder="Masukan Nama Produk">
                    <small class="text-danger error-name"></small>

                    <!-- DESKRIPSI -->
                    <h6 class="mb-2 mt-3">Deskripsi Produk</h6>
                    <textarea name="description" class="form-control mb-2" placeholder="Masukan Deskripsi Kategori"
                        style="height:50px; resize:none;"></textarea>
                    <small class="text-danger error-description"></small>

                    <!-- HARGA -->
                    <h6 class="mb-2 mt-3 border-top pt-3">Harga Produk</h6>
                    <input type="number" name="price" class="form-control" placeholder="Masukan Harga Produk">
                    <small class="text-danger error-price"></small>

                    <!-- ROW -->
                    <div class="row mt-3 border-top pt-3">
                        <div class="col-md-6 ">
                            <h6 class="mb-2 ">Stok</h6>
                            <input type="number" name="stock" class="form-control" placeholder="Masukan Stok Produk"
                                min="0" step="1">
                        </div>

                        <div class="col-md-6">
                            <h6 class="mb-2">Berat Produk</h6>
                            <input type="number" name="weight_kg" class="form-control"
                                placeholder="Masukan Berat Produk Kg/Liter" min="0" step="0.01">
                        </div>
                    </div>
                    <div class="row mt-3 border-top pt-3">

                        <div class="col-md-3">
                            <select name="category_id" class="form-select text-muted">
                                <option value="">Pilih Kategori</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            <small class="text-danger error-category_id"></small>
                        </div>
                    </div>


                    {{-- <div class="form-check form-switch mt-4">
                        <input class="form-check-input" type="checkbox" id="isActive" checked>
                        <label class="form-check-label">Tampilkan ke Produk Terlaris</label>
                    </div> --}}

                    <!-- STATUS -->
                    <div class="form-check form-switch mt-4">
                        <input class="form-check-input" type="checkbox" id="isActive" checked>
                        <label class="form-check-label">Tampilkan Produk</label>
                    </div>

                </div>
            </div>

            <!-- RIGHT -->
            <div class="col-md-4 d-flex">
                <div class="card p-4 border-0 shadow-sm w-100 h-100 d-flex flex-column">

                    <h6 class="mb-3">Gambar Produk</h6>

                    <label id="dropArea"
                        class="border rounded w-100 flex-grow-1 d-flex justify-content-center align-items-center text-center"
                        style="cursor:pointer; min-height:220px; overflow:hidden; position:relative;">

                        <div id="uploadContent">
                            <div class="btn-image-admin fw-medium">Masukan Gambar</div>
                            <div class="text-muted small">Seret dan Taruh Gambar</div>
                        </div>

                    </label>

                    <input type="file" name="images[]" id="imageInput" class="d-none" accept="image/*">

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
            const dropArea = document.getElementById('dropArea');
            const uploadContent = document.getElementById('uploadContent');
            const btnSubmit = document.getElementById('btnSubmit');

            // =========================
            // 🚫 BLOCK DRAG GLOBAL (FIX TAB BARU)
            // =========================
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(event => {
                document.body.addEventListener(event, function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                }, false);
            });

            // =========================
            // PREVIEW FUNCTION
            // =========================
            function showPreview(file) {
                if (!file || !file.type.startsWith('image/')) {
                    alert('File harus gambar!');
                    return;
                }

                const url = URL.createObjectURL(file);

                // sembunyikan icon upload
                uploadContent.style.display = 'none';

                // hapus gambar lama
                let oldImg = dropArea.querySelector('img');
                if (oldImg) oldImg.remove();

                // buat wrapper biar center
                const wrapper = document.createElement('div');
                wrapper.style.cssText = `
        width:100%;
        height:100%;
        display:flex;
        justify-content:center;
        align-items:center;
        position:absolute;
        top:0;
        left:0;
    `;

                // gambar
                const img = document.createElement('img');
                img.src = url;
                img.style.cssText = `
        max-width:100%;
        max-height:100%;
        object-fit:contain;
    `;

                wrapper.appendChild(img);
                dropArea.appendChild(wrapper);
            }

            // =========================
            // CLICK
            // =========================
            dropArea.addEventListener('click', () => input.click());

            input.addEventListener('change', function(e) {
                showPreview(e.target.files[0]);
            });

            // =========================
            // DRAG AREA
            // =========================
            dropArea.addEventListener('dragover', function(e) {
                e.preventDefault();
                dropArea.classList.add('border-primary');
            });

            dropArea.addEventListener('dragleave', function() {
                dropArea.classList.remove('border-primary');
            });

            dropArea.addEventListener('drop', function(e) {
                e.preventDefault();
                dropArea.classList.remove('border-primary');

                const file = e.dataTransfer.files[0];

                if (file) {
                    const dt = new DataTransfer();
                    dt.items.add(file);
                    input.files = dt.files;

                    showPreview(file);
                }
            });

            // =========================
            // SUBMIT
            // =========================
            form.addEventListener('submit', async function(e) {
                e.preventDefault();

                document.querySelectorAll('.text-danger').forEach(el => el.innerText = '');

                let formData = new FormData(form);

                let fileInput = document.getElementById('imageInput');
                let file = fileInput.files[0];

                if (file) {
                    let maxSize = 1 * 1024 * 1024;

                    if (file.size > maxSize) {
                        showErrorModal('Ukuran gambar maksimal 1 MB');
                        fileInput.value = '';
                        return;
                    }

                    // WAJIB: pastikan masuk ke FormData
                    formData.append('images[]', file);
                }
                // set boolean
                formData.set('is_active', document.getElementById('isActive').checked ? 1 : 0);

                let token = localStorage.getItem('access_token');

                if (!token) {
                    alert('Session habis, login ulang!');
                    window.location.href = '/login';
                    return;
                }

                btnSubmit.disabled = true;
                btnSubmit.innerText = 'Menyimpan...';

                try {
                    let response = await fetch('/api/admin/products', { // ✅ FIX ENDPOINT
                        method: 'POST',
                        body: formData,
                        headers: {
                            'Accept': 'application/json',
                            'Authorization': 'Bearer ' + token
                        }
                    });

                    let res = await response.json();

                    // VALIDATION ERROR
                    if (response.status === 422) {
                        let errors = res.errors;

                        if (errors.name) {
                            showErrorModal(errors.name[0]);
                            return;
                        }

                        if (errors.price) {
                            showErrorModal(errors.price[0]);
                            return;
                        }

                        if (errors.category_id) {
                            showErrorModal(errors.category_id[0]);
                            return;
                        }

                        if (errors['images.0']) {
                            showErrorModal(errors['images.0'][0]);
                            return;
                        }

                        return;
                    }

                    // SUCCESS
                    if (res.success) {
                        window.location.href = "/admin/product?created=1&id=" + res.data.id;
                    }

                } catch (err) {
                    console.error(err);
                    alert('Terjadi kesalahan server');
                } finally {
                    btnSubmit.disabled = false;
                    btnSubmit.innerText = 'Simpan';
                }
            });

            function showErrorModal(message) {
                document.getElementById('errorMessage').innerText = message;
                let modal = new bootstrap.Modal(document.getElementById('errorModal'));
                modal.show();
            }
        });
    </script>
@endsection
