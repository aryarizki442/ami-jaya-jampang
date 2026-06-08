<style>
    #paymentGuideModal .modal-dialog {
        max-width: 500px;
    }

    #paymentGuideModal .modal-content {
        overflow: hidden;
    }

    #paymentGuideModal .btn-link {
        font-size: 16px;
    }

    #paymentGuideModal #modalVaNumber,
    #paymentGuideModal #modalTotal {
        line-height: 1.2;
        font-size: 16px;

    }

    #paymentGuideModal #paymentSteps {
        font-size: 14px;
        color: #212529;
    }

    #paymentGuideModal #paymentSteps li {
        font-size: 14px;
        line-height: 1.5;
    }

    #copyVaBtn,
    #copyTotalBtn {
        transition: all 0.2s ease;
        min-width: 65px;
    }

    #copyVaBtn:active,
    #copyTotalBtn:active {
        transform: scale(0.95);
    }
</style>

{{-- Modal Cara Pembayaran --}}
<div class="modal fade" id="paymentGuideModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content border-0 rounded-4">

            <div class="modal-header border-bottom px-4 py-3">
                <h5 class="fw-bold mb-0">Cara Pembayaran</h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="border-bottom"> </div>


            <div class="modal-body p-0 px-4">

                <div class="p-2 border-bottom">

                    <!-- BANK -->
                    <div class="d-flex justify-content-between align-items-center">

                        <h6 class="fw-bold mb-0" id="modalBankName">
                            BCA Virtual Account
                        </h6>

                        <img id="modalBankLogo" src="/images/payments/bank/bca.png"
                            style="width:72px;height:auto;object-fit:contain;">
                    </div>

                    <!-- VA -->
                    <div class="mb-2">

                        <div class="text-muted small">
                            Nomor Virtual Account
                        </div>

                        <div class="d-flex justify-content-between align-items-center">

                            <div class="fw-bold" id="modalVaNumber">
                                -
                            </div>

                            <button
                                class="btn btn-link text-success text-decoration-none p-0 fw-semibold d-flex align-items-center gap-1"
                                id="copyVaBtn">

                                Salin

                                <iconify-icon icon="solar:notes-outline" width="16">
                                </iconify-icon>

                            </button>

                        </div>

                    </div>

                    <!-- TOTAL -->
                    <div>

                        <div class="text-muted small">
                            Total Tagihan
                        </div>

                        <div class="d-flex justify-content-between align-items-center">

                            <div class="fw-bold" id="modalTotal">
                                -
                            </div>

                            <button
                                class="btn btn-link text-success text-decoration-none p-0 fw-semibold d-flex align-items-center gap-1"
                                id="copyTotalBtn">

                                Salin

                                <iconify-icon icon="solar:notes-outline" width="16">
                                </iconify-icon>

                            </button>

                        </div>

                    </div>

                </div>

                <div class="p-2">

                    <h6 class="fw-bold mb-3">
                        Virtual Account Bank
                    </h6>

                    <ol class="ps-3 text-muted mb-0" id="paymentSteps">
                    </ol>

                </div>

            </div>

        </div>
    </div>
</div>
<script>
    document.addEventListener('click', function(e) {

        const btn = e.target.closest('.btn-payment-guide');
        if (!btn) return;

        const bank = btn.dataset.bank;
        const va = btn.dataset.va;
        const total = btn.dataset.total;

        document.getElementById('modalBankName').innerText = bank;
        document.getElementById('modalVaNumber').innerText = va;
        document.getElementById('modalTotal').innerText = total;

        const logoMap = {
            'BCA Virtual Account': '/images/payments/bank/bca.png',
            'BNI Virtual Account': '/images/payments/bank/bni.png',
            'BRI Virtual Account': '/images/payments/bank/bri.png',
            'Mandiri Virtual Account': '/images/payments/bank/mandiri.png'
        };

        document.getElementById('modalBankLogo').src =
            logoMap[bank] || '/images/payments/bank/bca.png';

        document.getElementById('paymentSteps').innerHTML = `
        <li>Siapkan Nomor Virtual Account Anda <strong>${va}</strong></li>
        <li>
            Pilih Metode Pembayaran
            <ul>
                <li>ATM</li>
                <li>Mobile Banking (m-Banking)</li>
                <li>Internet Banking</li>
                <li>Teller Bank</li>
            </ul>
        </li>
        <li>
            Pilih Menu Pembayaran / VA
            <ul>
                <li>Masuk ke Menu</li>
                <li>Pembayaran atau Bayar</li>
                <li>Transfer → Virtual Account</li>
            </ul>
        </li>
        <li>
            Masukkan Kode Virtual Account
            <ul>
                <li>Masukan Kode VA yang sudah diberikan </li>
                <li>Pastikan tidak ada kesalahan angka</li>
            </ul>
        </li>
        <li>Masukkan jumlah transfer sesuai Total Tagihan</li>
        <li>Ikuti instruksi hingga transaksi selesai</li>
        <li>Simpan bukti pembayaran</li>
    `;

        bootstrap.Modal
            .getOrCreateInstance(document.getElementById('paymentGuideModal'))
            .show();
    });

    // Fungsi untuk menyalin teks
    function copyToClipboard(text, element) {
        navigator.clipboard.writeText(text).then(function() {
            // Simpan teks asli
            const originalText = element.innerHTML;

            // Ubah teks menjadi "Tersalin!" dengan icon centang
            element.innerHTML = `
            Tersalin!
            <iconify-icon icon="solar:check-read-outline" width="16"></iconify-icon>
        `;

            // Kembalikan ke teks asli setelah 1.5 detik
            setTimeout(() => {
                element.innerHTML = originalText;
            }, 1500);
        }).catch(function(err) {
            console.error('Gagal menyalin: ', err);
            // Tampilkan pesan gagal
            const originalText = element.innerHTML;
            element.innerHTML = `
            Gagal!
            <iconify-icon icon="solar:close-circle-outline" width="16"></iconify-icon>
        `;
            setTimeout(() => {
                element.innerHTML = originalText;
            }, 1500);
        });
    }

    // Event listener untuk tombol salin VA
    document.addEventListener('click', function(e) {
        const copyVaBtn = e.target.closest('#copyVaBtn');
        if (copyVaBtn) {
            e.preventDefault();
            const vaNumber = document.getElementById('modalVaNumber').innerText;
            if (vaNumber && vaNumber !== '-') {
                copyToClipboard(vaNumber, copyVaBtn);
            }
        }
    });

    // Event listener untuk tombol salin Total
    document.addEventListener('click', function(e) {
        const copyTotalBtn = e.target.closest('#copyTotalBtn');
        if (copyTotalBtn) {
            e.preventDefault();
            let total = document.getElementById('modalTotal').innerText;
            if (total && total !== '-') {
                copyToClipboard(total, copyTotalBtn);
            }
        }
    });
</script>
