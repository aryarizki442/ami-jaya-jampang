@extends('backend.app')

@section('title', 'Kategori')

@section('content')

    <style>
        #dropArea.border-primary {
            background: #f8f9fa;
            border: 2px dashed #0d6efd !important;
        }
    </style>

    <form id="formKategori" enctype="multipart/form-data">
        <div class="row g-2 align-items-stretch">
            <div class="d-flex align-items-center mt-0">
                <a href="{{ route('admin.category') }}" class="d-flex align-items-center text-decoration-none text-dark">
                    <i class="ri-arrow-left-line fs-5 me-2"></i>
                    <span class="fw-medium">Kembali</span>
                </a>
            </div>
            <h5 class="fw-semibold">Tambah Kategori</h5>

            <div class="col-md-8 d-flex">
                <div class="card p-4 border-0 shadow-sm w-100 h-100 d-flex flex-column">

                    <h6 class="mb-3">Nama Kategori</h6>
                    <input type="text" name="name" class="form-control mb-2" placeholder="Masukan Nama Kategori">
                    <small class="text-danger error-name"></small>

                    <h6 class="mb-2 mt-3">Deskripsi Kategori</h6>
                    <textarea name="description" class="form-control mb-2" placeholder="Masukan Deskripsi Kategori"
                        style="height:50px; resize:none;"></textarea>
                    <small class="text-danger error-description"></small>

                    <div class="form-check form-switch mt-3">
                        <input class="form-check-input" type="checkbox" id="isActive" checked>
                        <label class="form-check-label">Tampilkan Kategori</label>
                    </div>

                </div>
            </div>

            <div class="col-md-4 d-flex">
                <div class="card p-4 border-0 shadow-sm w-100 h-100 d-flex flex-column">

                    <h6 class="mb-3">Gambar Kategori</h6>

                    <label id="dropArea"
                        class="border rounded w-100 flex-grow-1 d-flex justify-content-center align-items-center text-center"
                        style="cursor:pointer; min-height:220px; overflow:hidden; position:relative;">

                        <div id="uploadContent">
                            <button type="button" class="btn btn-image-admin fw-medium mb-2">
                                Masukan Gambar
                            </button>
                            <div class="small text-neutral-custom">Klik atau seret gambar</div>
                        </div>

                    </label>

                    <input type="file" name="image" id="imageInput" class="d-none" accept="image/*">

                    <small class="text-danger error-image mt-2 d-block text-center"></small>

                </div>
            </div>

        </div>

        <!-- BUTTON -->
        <div class="mt-3 border-top pt-3 d-flex justify-content-end gap-2">
            <a href="{{ route('admin.category') }}" class="btn btn-second">Batal</a>
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

            const form = document.getElementById('formKategori');
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

                // ✅ VALIDASI FILE SIZE (1 MB)
                let fileInput = form.querySelector('input[name="image"]');
                let file = fileInput.files[0];

                if (file) {
                    let maxSize = 1 * 1024 * 1024;

                    if (file.size > maxSize) {
                        showErrorModal('Ukuran gambar maksimal 1 MB');
                        fileInput.value = '';
                        return;
                    }
                }

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
                    let response = await fetch('/api/admin/categories', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'Accept': 'application/json',
                            'Authorization': 'Bearer ' + token
                        }
                    });

                    let res = await response.json();

                    if (response.status === 422) {
                        let errors = res.errors;

                        // PRIORITAS: nama sudah ada
                        if (errors.name) {
                            showErrorModal(errors.name[0]); // otomatis: "nama sudah ada"
                            return;
                        }

                        if (errors.description) {
                            showErrorModal(errors.description[0]);
                            return;
                        }

                        if (errors.image) {
                            showErrorModal(errors.image[0]);
                            return;
                        }

                        return;
                    }

                    if (res.success) {
                        window.location.href = "{{ route('admin.category') }}" + "?created=1&id=" + res
                            .data.id;
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
