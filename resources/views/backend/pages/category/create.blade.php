@extends('backend.app')

@section('title', 'Tambah Kategori')

@section('content')

    <style>
        #dropArea.border-primary {
            background: #f8f9fa;
            border: 2px dashed #0d6efd !important;
        }
    </style>

    <form id="formKategori" enctype="multipart/form-data">
        <div class="row g-4 align-items-stretch">

            <!-- KIRI -->
            <div class="col-md-8 d-flex">
                <div class="card p-4 border-0 shadow-sm w-100 h-100">

                    <h6 class="mb-3">Nama Kategori</h6>
                    <input type="text" name="name" class="form-control mb-2">
                    <small class="text-danger error-name"></small>

                    <h6 class="mb-2 mt-3">Deskripsi Kategori</h6>
                    <textarea name="description" class="form-control mb-2" rows="4"></textarea>
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
                            <i class="ri-upload-cloud-2-line fs-2 text-primary mb-2"></i>
                            <div class="text-primary fw-medium">Masukan Gambar</div>
                            <div class="text-muted small">Klik atau seret gambar</div>
                        </div>

                    </label>

                    <input type="file" name="image" id="imageInput" class="d-none" accept="image/*">

                    <small class="text-danger error-image mt-2 d-block text-center"></small>

                </div>
            </div>

        </div>

        <!-- BUTTON -->
        <div class="mt-3 border-top pt-3 d-flex justify-content-end gap-2">
            <a href="{{ route('admin.category') }}" class="btn btn-outline-secondary">Batal</a>
            <button type="submit" id="btnSubmit" class="btn btn-success">Simpan</button>
        </div>
    </form>




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

                        if (errors.name) document.querySelector('.error-name').innerText = errors.name[
                            0];
                        if (errors.description) document.querySelector('.error-description').innerText =
                            errors.description[0];
                        if (errors.image) document.querySelector('.error-image').innerText = errors
                            .image[0];

                        return;
                    }

                    if (res.success) {
                        alert(res.message);
                        window.location.href = "{{ route('admin.category') }}";
                    }

                } catch (err) {
                    console.error(err);
                    alert('Terjadi kesalahan server');
                } finally {
                    btnSubmit.disabled = false;
                    btnSubmit.innerText = 'Simpan';
                }
            });

        });
    </script>
@endsection
