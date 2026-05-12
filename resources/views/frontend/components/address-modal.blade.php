<div class="modal fade" id="alamatModal" tabindex="-1">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title fw-semibold">Alamat Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <form id="addressForm">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" name="recipient_name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nomor Telepon</label>
                            <input type="text" class="form-control" name="phone" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Provinsi</label>
                        <input type="text" class="form-control" name="province" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kota/Kabupaten</label>
                        <input type="text" class="form-control" name="city" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kecamatan</label>
                        <input type="text" class="form-control" name="district">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kelurahan/Desa</label>
                        <input type="text" class="form-control" name="village">
                    </div>

                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label class="form-label">Detail Lainnya</label>
                            <textarea class="form-control" rows="3" name="detail"></textarea>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Kode Pos</label>
                            <input type="text" class="form-control" name="postal_code">
                        </div>
                    </div>

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

                        <!-- BUTTON -->
                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">
                                Batal
                            </button>

                            <button type="submit" class="btn btn-success btn-sm" id="saveAddressBtn">
                                Simpan
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
<script>
    document.getElementById('addressForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const token = localStorage.getItem('token');

        if (!token) {
            showGuestLoginModal();
            return;
        }

        const getValue = (name) =>
            document.querySelector(`[name="${name}"]`)?.value || '';

        try {

            // =========================
            // CHECK JUMLAH ADDRESS
            // =========================
            const checkRes = await fetch('/api/addresses', {
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Accept': 'application/json'
                }
            });

            const checkJson = await checkRes.json();

            const addresses = checkJson.data || [];

            if (addresses.length >= 3) {
                alert('Maksimal hanya 3 alamat');
                return;
            }

            // =========================
            // DATA CREATE
            // =========================
            const data = {
                label: getActiveLabel(),
                recipient_name: getValue('recipient_name'),
                phone: getValue('phone'),
                province: getValue('province'),
                city: getValue('city'),
                district: getValue('district'),
                village: getValue('village'),
                detail: getValue('detail'),
                postal_code: getValue('postal_code'),
                is_primary: false
            };

            // =========================
            // CREATE ADDRESS
            // =========================
            const res = await fetch('/api/addresses', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'Authorization': 'Bearer ' + token
                },
                body: JSON.stringify(data)
            });

            const result = await res.json().catch(() => ({}));

            if (!res.ok) {
                alert(result.message || 'Gagal menyimpan alamat');
                return;
            }

            console.log('Alamat tersimpan:', result.data);

            loadUserAddress?.();

            const modalEl = document.getElementById('alamatModal');
            bootstrap.Modal.getOrCreateInstance(modalEl).hide();

            this.reset();

            setActiveLabel('home');

        } catch (err) {
            console.error(err);
            alert('Server error');
        }
    });

    function getActiveLabel() {
        return document.querySelector('.address-label.active')?.dataset.label || 'home';
    }

    function setActiveLabel(label = 'home') {
        document.querySelectorAll('.address-label')
            .forEach(b => {
                b.classList.toggle('active', b.dataset.label === label);
            });
    }

    function setActiveLabel(label = 'home') {
        document.querySelectorAll('.address-label')
            .forEach(b => {
                b.classList.toggle('active', b.dataset.label === label);
            });
    }

    function openAlamatModal() {

        const alamatModalEl = document.getElementById('alamatModal');
        const listModalEl = document.getElementById('addressListModal');

        const showAlamatModal = () => {

            // cleanup backdrop nyangkut
            document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
            document.body.classList.remove('modal-open');

            const modal = bootstrap.Modal.getOrCreateInstance(alamatModalEl);
            modal.show();
        };

        // kalau modal list sedang terbuka
        if (listModalEl && listModalEl.classList.contains('show')) {

            listModalEl.addEventListener('hidden.bs.modal', function handler() {

                listModalEl.removeEventListener('hidden.bs.modal', handler);

                showAlamatModal();
            });

            bootstrap.Modal.getInstance(listModalEl)?.hide();

        } else {
            showAlamatModal();
        }
    }

    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.address-label');
        if (!btn) return;

        setActiveLabel(btn.dataset.label);
    });
</script>
