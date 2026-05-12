<div class="modal fade" id="loginRequiredModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">

            <div class="modal-body text-center p-4">

                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">

                        <h5 class="mb-3 fw-semibold">
                            Silahkan login terlebih dahulu
                        </h5>

                        <p class="text-muted mb-4">
                            Untuk melanjutkan aksi ini, kamu harus masuk ke akun terlebih dahulu.
                        </p>

                        <a href="/login" class="btn btn-primary w-100">
                            Login
                        </a>

                    </div>
                </div>

            </div>

        </div>
    </div>
</div>

<script>
    function showGuestLoginModal() {
        const modalEl = document.getElementById('loginRequiredModal');

        if (!modalEl) {
            console.error('Modal login tidak ditemukan');
            return;
        }

        bootstrap.Modal.getOrCreateInstance(modalEl).show();
    }
</script>
