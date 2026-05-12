<style>
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
</style>

<div class="modal fade" id="addressListModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 rounded-4 overflow-hidden">

            <!-- HEADER -->
            <div class="modal-header py-3 px-4 border-0">
                <h5 class="modal-title fw-bold">
                    Alamat Saya
                </h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal">
                </button>
            </div>

            <!-- BODY -->
            <div class="modal-body px-4 pb-4">

                <div id="addressListContainer"></div>

                <!-- BUTTON TAMBAH -->
                <button class="btn btn-main w-100 rounded-3 py-2 mt-3" onclick="openAlamatModal()">

                    <span class="iconify me-1" data-icon="ic:round-plus">
                    </span>

                    Tambah Alamat Baru
                </button>

            </div>

        </div>
    </div>
</div>

<script>
    async function loadAddressList() {

        const token = localStorage.getItem('token');
        const container = document.getElementById('addressListContainer');

        if (!token) return;

        const res = await fetch('/api/addresses', {
            headers: {
                'Authorization': 'Bearer ' + token,
                'Accept': 'application/json'
            }
        });

        const json = await res.json();

        if (!json.success) return;

        const addresses = json.data || [];

        const isMobile = window.innerWidth < 768;

        container.innerHTML = '';

        addresses.forEach(address => {

            const isPrimary = address.is_primary;

            const html = `
               <div class="address-card border-top py-4">

    <div class="d-flex justify-content-between align-items-start gap-3
        ${isMobile ? 'flex-column' : ''}">

        <!-- LEFT -->
        <div class="d-flex align-items-start gap-3 flex-grow-1">

            <!-- RADIO SELECT -->
            <div class="pt-1">
              <input
    type="radio"
    name="selected_address"
    class="form-check-input address-radio"
    value="${address.id}"
    onchange='selectAddress(${JSON.stringify(address)})'
    style="cursor:pointer;">
            </div>

            <!-- CONTENT -->
            <div class="flex-grow-1">

                <div class="d-flex align-items-center flex-wrap mb-2">

                    <strong class="me-3 fw-bold">
                        ${address.recipient_name}
                    </strong>

                    <span class="divider me-3"></span>

                    <span class="text-muted">
                        ${address.phone}
                    </span>

                </div>

                <p class="text-muted mb-3 small">
                    ${address.detail ?? ''},
                    ${address.village ?? ''},
                    ${address.district ?? ''},
                    ${address.city ?? ''},
                    ${address.province ?? ''},
                    ${address.postal_code ?? ''}
                </p>

                <div class="d-flex align-items-center gap-2 text-success">

                    <span class="iconify"
                        data-icon="${address.label === 'home'
                            ? 'ic:baseline-house'
                            : 'vaadin:office'}"
                        style="${isMobile ? 'font-size:18px' : 'font-size:20px'}">
                    </span>

                    <span style="${isMobile ? 'font-size:13px' : ''}">
                        ${address.label === 'home'
                            ? 'Rumah'
                            : 'Kantor'}
                    </span>

                </div>

            </div>

        </div>

        <!-- RIGHT -->
        <div class="${isMobile ? 'w-100' : 'text-end'} d-flex flex-column justify-content-between h-100">

            <!-- TOP -->
            <div>
                ${isPrimary ? `
                    <div class="premium-category fw-semibold small text-success">
                        Alamat Utama
                    </div>
                ` : ''}
            </div>

            <!-- BOTTOM -->
            <div class="mt-5">
                <a href="#"
                    class="ubah-link"
                    data-id="${address.id}">
                    Ubah
                </a>
            </div>

        </div>

    </div>

</div>
            `;

            container.insertAdjacentHTML('beforeend', html);
        });

        // render ulang iconify
        if (window.Iconify) {
            Iconify.scan();
        }
    }

    function selectAddress(address) {

        // render address terpilih
        renderAddress(address);

        // update radio terpilih
        document.querySelectorAll('.address-radio').forEach(radio => {
            radio.checked = Number(radio.value) === Number(address.id);
        });
    }
</script>
