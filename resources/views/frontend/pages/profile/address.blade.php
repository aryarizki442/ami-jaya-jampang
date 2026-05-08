@extends('frontend.pages.profile.account')

@section('title', 'Alamat Saya')

@section('account-content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .myAddress {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
        }

        .profile-header-top {
            border-bottom: 2px solid #e5e5e5;
            margin-top: 10px;
            padding-bottom: 30px;
        }

        .profile-header {
            border-bottom: 2px solid #e5e5e5;
        }

        .address-card {
            padding: 15px 0;
        }

        .divider {
            width: 1.5px;
            height: 22px;
            background: #e5e5e5;
        }

        .address-card a {
            text-decoration: none;
            font-size: 14px;
        }

        .address-card .btn-success {
            background: #1F7D53;
            border: none;
        }

        .ubah-link {
            color: #57A5E8;
            text-decoration: none;
        }

        .ubah-link:hover {
            text-decoration: underline;
        }

        .address-btn {
            min-width: 220px;
            text-align: center;
        }

        /* MODAL CLEAN */
        #deleteModal .modal-content {
            border: none;
            box-shadow: none;
        }

        #deleteModal .modal-header,
        #deleteModal .modal-footer {
            border: none;
        }

        @media (max-width: 768px) {

            .address-card .d-flex.justify-content-between {
                flex-direction: column;
                align-items: flex-start !important;
                gap: 12px;
            }

            .address-card .text-end {
                width: 100%;
                text-align: left !important;
            }

            .address-card .mt-4 {
                margin-top: 10px !important;
            }

            .address-card .address-btn {
                width: 100%;
            }

            .address-card .divider {
                display: none;
            }

            .address-card p {
                font-size: 13px;
                line-height: 1.4;
            }

            .address-card .ubah-link {
                font-size: 13px;
            }

            .address-card strong {
                font-size: 14px;
            }

            .divider {
                display: none;
            }

            .profile-header-top {
                flex-direction: column;
                align-items: flex-start !important;
                gap: 10px;
            }

            .address-btn {
                width: 100%;
                justify-content: center;
                font-size: 14px;
            }

            .address-btn .iconify {
                font-size: 18px;
            }

        }
    </style>

    <section class="myAddress">
        <div class="d-flex align-items-center justify-content-between profile-header-top">
            <h5 class="mb-0 ">Alamat Saya</h5>
            <button class="btn btn-main btn-sm address-btn d-flex align-items-center justify-content-center gap-2 px-3"
                data-bs-toggle="modal" data-bs-target="#alamatModal">

                <span class="iconify" data-icon="ic:round-plus"></span>
                <span>Tambah Alamat Baru</span>

            </button>
        </div>

        <div class="address-card">
            <div id="addressList"></div>
        </div>

    </section>

    <div class="modal fade" id="alamatModal" tabindex="-1">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">

                <!-- HEADER -->
                <div class="modal-header">
                    <h5 class="modal-title fw-semibold">Alamat Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <!-- BODY -->
                <div class="modal-body">

                    <!-- Nama & Telepon -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" name="recipient_name">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nomor Telepon</label>
                            <input type="text" class="form-control" name="phone">
                        </div>
                    </div>

                    <!-- Wilayah -->
                    <div class="mb-3">
                        <label class="form-label">Provinsi</label>
                        <input type="text" class="form-control " name="province">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kota/Kabupaten</label>
                        <input type="text" class="form-control" name="city">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kecamatan</label>
                        <input type="text" class="form-control" name="district">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kelurahan/Desa</label>
                        <input type="text" class="form-control" name="village">
                    </div>

                    <!-- Detail + Kode Pos -->
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label class="form-label">Detail Lainnya</label>
                            <textarea class="form-control" rows="3" name="detail"></textarea>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Kode Pos</label>
                            <input type="text" class="form-control" name="postal_code" maxlength="10">
                        </div>
                    </div>

                    <!-- Tandai Sebagai -->
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mt-4">

                        <!-- Kiri -->
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-outline-secondary btn-sm address-label active"
                                data-label="home">
                                Rumah
                            </button>

                            <button type="button" class="btn btn-outline-secondary btn-sm address-label"
                                data-label="office">
                                Kantor
                            </button>
                        </div>

                        <!-- Kanan -->
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-light btn-outline-success btn-sm" data-bs-dismiss="modal">
                                Nanti Saja
                            </button>
                            <button type="button" class="btn btn-success btn-sm">
                                Simpan
                            </button>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>


    {{-- Success Modal --}}
    <div class="modal fade" id="modalSuccess" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content p-4 text-center border-0">

                <div class="mb-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto"
                        style="width:50px;height:50px;background:#22C55E;">
                        <i class="iconify text-white fs-3" data-icon="iconamoon:check-bold"></i>
                    </div>
                </div>

                <p class="fw-medium mb-0" id="successMessage">
                    Berhasil Menambah Alamat
                </p>

            </div>
        </div>
    </div>

    {{-- Modal Delete Address --}}
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content p-3 text-center">

                <div class="modal-header justify-content-center border-0 p-0 mb-3">
                    <h5 class="fw-semibold m-0">Hapus Alamat?</h5>
                </div>

                <div class="modal-footer justify-content-center gap-2 border-0 p-0">
                    <button class="btn btn-delete-second" data-bs-dismiss="modal">
                        Nanti Saja
                    </button>

                    <button class="btn btn-delete-main text-custom-red" id="confirmDeleteBtn">
                        Hapus
                    </button>
                </div>

            </div>
        </div>
    </div>

    {{-- Success Modal --}}
    <div class="modal fade" id="deleteSuccessModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content p-4 text-center border-0">

                <div class="mb-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto"
                        style="width:50px;height:50px;background:#22C55E;">
                        <i class="iconify text-white fs-3" data-icon="iconamoon:check-bold"></i>
                    </div>
                </div>

                <p class="fw-medium mb-0">Berhasil Menghapus Alamat</p>

            </div>
        </div>
    </div>
    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const modal = document.getElementById('alamatModal');
            const saveBtn = modal.querySelector('.btn-success');
            const isMobile = window.innerWidth < 768;

            async function loadAddresses() {
                const token = localStorage.getItem('token');

                if (!token) return [];

                try {
                    const res = await fetch('/api/addresses', {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'Authorization': 'Bearer ' + token
                        }
                    });

                    const result = await res.json();

                    if (!res.ok) {
                        console.error(result.message);
                        return [];
                    }

                    const data = Array.isArray(result.data) ? result.data : [];

                    addressCount = data.length;

                    const container = document.getElementById('addressList');
                    container.innerHTML = '';

                    if (!data || data.length === 0) {
                        container.innerHTML = `
        <div class="address-card profile-header d-flex flex-column align-items-center justify-content-center text-center py-5">
            <img src="{{ asset('images/home/profile/not-address.png') }}"
                 alt="No Address"
                 style="width: 100px; height: auto; margin-bottom: 12px;">

            <h6 class="mb-1 fw-semibold">Anda Belum Memiliki Alamat</h6>
        </div>
    `;
                        return [];
                    }

                    data.forEach(address => {
                        renderAddress(address);
                    });

                    return data;

                } catch (err) {
                    console.error(err);
                    return [];
                }
            }

            function renderAddress(address) {
                const container = document.getElementById('addressList');

                const isPrimary = address.is_primary ? true : false;

                const html = `
            <div class="address-card profile-header">
                <div class="d-flex justify-content-between align-items-start">

                    <div>
                        <div class="d-flex align-items-center mb-2">
                            <strong class="me-3 fw-bold">
                                ${address.recipient_name}
                            </strong>

                            <span class="divider"></span>

                            <span class="text-muted ms-3">
                                ${address.phone}
                            </span>
                        </div>

                     <p class="text-muted mb-2">
                        ${address.detail ?? ''},
                        ${address.village},
                        ${address.district},
                        ${address.city},
                        ${address.province},
                        ${address.postal_code ?? ''}
                    </p>

                     <div class="d-flex align-items-center gap-2 text-success">
                        <span class="iconify"
                            data-icon="${address.label === 'home' ? 'ic:baseline-house' : 'vaadin:office'}"
                            style="${isMobile ? 'font-size:18px' : ''}">
                        </span>

                        <span style="${isMobile ? 'font-size:13px' : ''}">
                            ${address.label === 'home' ? 'Rumah' : 'Kantor'}
                        </span>
                    </div>
                    </div>

                    <div class="text-end">
                      <div class="mt-4 d-flex justify-content-end gap-3">
                        <a href="#"
                        class="ubah-link me-3"
                        data-id="${address.id}">
                            Ubah
                        </a>

                        <a href="#"
                        class="ubah-link delete-address"
                        data-id="${address.id}">
                            Hapus
                        </a>
                    </div>

                    <button
                        class="btn ${isPrimary ? 'btn-address-main' : 'btn-address-second'} btn-sm px-4 mt-2 address-btn set-primary"
                        data-id="${address.id}">
                        ${isPrimary ? 'Alamat Utama' : 'Jadikan Alamat Utama'}
                    </button>
                    </div>

                </div>
            </div>
        `;

                container.insertAdjacentHTML('beforeend', html);
            }

            document.addEventListener('click', async function(e) {
                const btn = e.target.closest('.set-primary');
                if (!btn) return;

                const id = btn.dataset.id;
                const token = localStorage.getItem('token');

                if (!token) {
                    alert('Silakan login dulu');
                    return;
                }

                try {
                    const res = await fetch(`/api/addresses/${id}/set-primary`, {
                        method: 'PATCH',
                        headers: {
                            'Accept': 'application/json',
                            'Authorization': 'Bearer ' + token
                        }
                    });

                    const result = await res.json();

                    if (!res.ok) {
                        alert(result.message || 'Gagal update alamat utama');
                        return;
                    }

                    // =========================
                    // 🔥 UPDATE UI TANPA RELOAD
                    // =========================

                    document.querySelectorAll('.address-card').forEach(card => {

                        const button = card.querySelector('.set-primary');

                        if (!button) return;

                        // semua reset jadi non-primary
                        button.classList.remove('btn-address-main');
                        button.classList.add('btn-address-second');
                        button.innerText = 'Jadikan Alamat Utama';
                    });

                    // tombol yang diklik jadi primary
                    btn.classList.remove('btn-address-second');
                    btn.classList.add('btn-address-main');
                    btn.innerText = 'Alamat Utama';

                } catch (err) {
                    console.error(err);
                    alert('Server error');
                }
            });

            // =========================
            // GET ACTIVE LABEL
            // =========================
            document.querySelectorAll('.address-label').forEach(btn => {
                btn.addEventListener('click', function() {
                    document.querySelectorAll('.address-label').forEach(b => b.classList.remove(
                        'active'));
                    this.classList.add('active');
                });
            });
            // =========================
            // SAVE ADDRESS
            // =========================

            function getActiveLabel() {
                const active = document.querySelector('.address-label.active');
                return active ? active.dataset.label : 'home';
            }
            saveBtn.addEventListener('click', async function() {

                // hanya cek limit saat tambah alamat baru
                if (!editId) {

                    const dataList = await loadAddresses();
                    addressCount = dataList.length;

                    if (addressCount >= 3) {
                        alert('Maksimal hanya 3 alamat');
                        return;
                    }
                }

                const token = localStorage.getItem('token');

                if (!token) {
                    alert('Silakan login dulu');
                    return;
                }

                const data = {
                    label: getActiveLabel(),
                    recipient_name: document.querySelector('[name="recipient_name"]').value,
                    phone: document.querySelector('[name="phone"]').value,
                    province: document.querySelector('[name="province"]').value,
                    city: document.querySelector('[name="city"]').value,
                    district: document.querySelector('[name="district"]').value,
                    village: document.querySelector('[name="village"]').value,
                    detail: document.querySelector('[name="detail"]').value,
                    postal_code: document.querySelector('[name="postal_code"]').value,
                    is_primary: false
                };

                try {

                    const url = editId ?
                        `/api/addresses/${editId}` :
                        '/api/addresses';

                    const method = editId ?
                        'PUT' :
                        'POST';

                    const res = await fetch(url, {
                        method: method,
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'Authorization': 'Bearer ' + token
                        },
                        body: JSON.stringify(data)
                    });

                    const result = await res.json();

                    if (!res.ok) {
                        alert(result.message || 'Gagal menyimpan alamat');
                        return;
                    }

                    const successModal = bootstrap.Modal.getOrCreateInstance(
                        document.getElementById('modalSuccess')
                    );

                    document.getElementById('successMessage').innerText =
                        editId ?
                        'Berhasil Mengubah Alamat' :
                        'Berhasil Menambah Alamat';

                    setTimeout(() => {
                        successModal.hide();
                    }, 1500);

                    await loadAddresses();

                    // close modal
                    const instance = bootstrap.Modal.getInstance(modal);
                    if (instance) instance.hide();

                    // reset form
                    modal.querySelectorAll('input, textarea')
                        .forEach(el => el.value = '');

                    // reset label
                    document.querySelectorAll('.address-label')
                        .forEach(b => b.classList.remove('active'));

                    document.querySelector('[data-label="home"]')
                        .classList.add('active');

                    // reset edit mode
                    editId = null;

                    // reset title modal
                    modal.querySelector('.modal-title').innerText = 'Alamat Baru';

                } catch (err) {
                    console.error(err);
                    alert('Server error');
                }
            });

            // =========================
            // EDIT ADDRESS
            // =========================

            let editId = null;

            // buka modal edit
            document.addEventListener('click', function(e) {

                const btn = e.target.closest('.ubah-link');
                if (!btn || btn.classList.contains('delete-address')) return;

                e.preventDefault();

                const card = btn.closest('.address-card');
                const id = card.querySelector('.set-primary').dataset.id;

                editId = id;

                const token = localStorage.getItem('token');

                fetch(`/api/addresses/${id}`, {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'Authorization': 'Bearer ' + token
                        }
                    })
                    .then(res => res.json())
                    .then(result => {

                        const address = result.data;

                        // isi form
                        document.querySelector('[name="recipient_name"]').value = address
                            .recipient_name ?? '';
                        document.querySelector('[name="phone"]').value = address.phone ?? '';
                        document.querySelector('[name="province"]').value = address.province ?? '';
                        document.querySelector('[name="city"]').value = address.city ?? '';
                        document.querySelector('[name="district"]').value = address.district ?? '';
                        document.querySelector('[name="village"]').value = address.village ?? '';
                        document.querySelector('[name="detail"]').value = address.detail ?? '';
                        document.querySelector('[name="postal_code"]').value = address.postal_code ??
                            '';

                        // active label
                        document.querySelectorAll('.address-label')
                            .forEach(b => b.classList.remove('active'));

                        document.querySelector(`[data-label="${address.label}"]`)
                            ?.classList.add('active');

                        // ubah title modal
                        modal.querySelector('.modal-title').innerText = 'Ubah Alamat';

                        // buka modal
                        bootstrap.Modal.getOrCreateInstance(modal).show();
                    })
                    .catch(err => {
                        console.error(err);
                        alert('Gagal mengambil data alamat');
                    });

            });



            // =========================
            // DELETE ADDRESS
            // =========================
            let deleteId = null;

            loadAddresses();

            // =========================
            // OPEN DELETE MODAL
            // =========================
            document.addEventListener('click', function(e) {

                const btn = e.target.closest('.delete-address');
                if (!btn) return;

                e.preventDefault();

                deleteId = btn.dataset.id;

                const modalEl = document.getElementById('deleteModal');
                const modalDelete = bootstrap.Modal.getOrCreateInstance(modalEl);

                modalDelete.show();
            });

            // =========================
            // CONFIRM DELETE
            // =========================
            document.getElementById('confirmDeleteBtn').addEventListener('click', async function() {

                const token = localStorage.getItem('token');
                if (!deleteId || !token) return;

                try {
                    const res = await fetch(`/api/addresses/${deleteId}`, {
                        method: 'DELETE',
                        headers: {
                            'Accept': 'application/json',
                            'Authorization': 'Bearer ' + token
                        }
                    });

                    let result = null;
                    try {
                        result = await res.json();
                    } catch (e) {}

                    if (!res.ok) {
                        alert(result?.message || 'Gagal menghapus alamat');
                        return;
                    }


                    deleteId = null;

                    // close modal
                    bootstrap.Modal.getInstance(document.getElementById('deleteModal'))
                        ?.hide();

                    // success modal
                    const successModal = bootstrap.Modal.getOrCreateInstance(
                        document.getElementById('deleteSuccessModal')
                    );

                    successModal.show();

                    setTimeout(() => successModal.hide(), 1500);

                } catch (err) {
                    console.error(err);
                    alert('Terjadi kesalahan, coba lagi');
                }

                const dataList = await loadAddresses();
                addressCount = dataList.length;

            });

        });
    </script>
@endsection
