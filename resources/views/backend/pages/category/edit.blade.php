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
        <div class="row g-4 align-items-stretch">

            <!-- KIRI -->
            <div class="col-md-8 d-flex">
                <div class="card p-4 border-0 shadow-sm w-100 h-100">

                    <h6 class="mb-3">Nama Kategori</h6>
                    <input type="text" name="name" id="name" class="form-control mb-2">
                    <small class="text-danger error-name"></small>

                    <h6 class="mb-2 mt-3">Deskripsi Kategori</h6>
                    <textarea name="description" id="description" class="form-control mb-2" rows="4"></textarea>
                    <small class="text-danger error-description"></small>

                    <div class="form-check form-switch mt-3">
                        <input class="form-check-input" type="checkbox" id="isActive">
                        <label class="form-check-label">Tampilkan Kategori</label>
                    </div>

                </div>
            </div>

            <!-- KANAN -->
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

            const categoryId = "{{ $id }}";
            const token = localStorage.getItem('access_token');

            // =========================
            // RENDER IMAGE (API + PREVIEW)
            // =========================
            function renderImage(url) {

                uploadContent.style.display = 'none';

                dropArea.querySelectorAll('.preview').forEach(e => e.remove());

                const wrapper = document.createElement('div');
                wrapper.classList.add('preview');
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
            // GET DATA EDIT (API)
            // =========================
            async function loadCategory() {
                try {

                    const res = await fetch(`/api/admin/categories/${categoryId}`, {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'Authorization': 'Bearer ' + token
                        }
                    });

                    const result = await res.json();

                    if (!result.success) return;

                    const c = result.data;

                    document.getElementById('name').value = c.name ?? '';
                    document.getElementById('description').value = c.description ?? '';
                    document.getElementById('isActive').checked = c.is_active == 1;

                    if (c.image) {
                        renderImage(`/storage/${c.image}`);
                    }

                } catch (err) {
                    console.error(err);
                }
            }

            loadCategory();

            // =========================
            // PREVIEW IMAGE BARU
            // =========================
            input.addEventListener('change', function() {
                const file = this.files[0];
                if (!file) return;

                renderImage(URL.createObjectURL(file));
            });

            dropArea.addEventListener('click', () => input.click());

            // =========================
            // UPDATE DATA (API)
            // =========================
            form.addEventListener('submit', async function(e) {
                e.preventDefault();

                document.querySelectorAll('.text-danger').forEach(e => e.innerText = '');

                const formData = new FormData(form);
                formData.set('is_active', document.getElementById('isActive').checked ? 1 : 0);
                formData.append('_method', 'PUT');

                btnSubmit.disabled = true;
                btnSubmit.innerText = 'Loading...';

                try {

                    const res = await fetch(`/api/admin/categories/${categoryId}`, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'Accept': 'application/json',
                            'Authorization': 'Bearer ' + token
                        }
                    });

                    const result = await res.json();

                    if (res.status === 422) {

                        if (result.errors?.name) {
                            document.querySelector('.error-name').innerText = result.errors.name[0];
                        }

                        if (result.errors?.description) {
                            document.querySelector('.error-description').innerText = result.errors
                                .description[0];
                        }

                        if (result.errors?.image) {
                            document.querySelector('.error-image').innerText = result.errors.image[0];
                        }

                        return;
                    }

                    if (result.success) {
                        window.location.href = "{{ route('admin.category') }}";
                    }

                } catch (err) {
                    console.error(err);
                    alert('Server error');
                } finally {
                    btnSubmit.disabled = false;
                    btnSubmit.innerText = 'Update';
                }
            });

        });
    </script>
@endsection
