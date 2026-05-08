function formatRupiahInput(el) {
    if (!el) return;

    el.addEventListener('input', function () {
        let raw = this.value.replace(/\D/g, '');

        if (!raw) {
            this.value = '';
            return;
        }

        this.value = new Intl.NumberFormat('id-ID').format(raw);
    });
}

function formatWeightInput(el) {
    if (!el) return;

    el.addEventListener('input', function () {
        let raw = this.value.replace(/\D/g, '');
        this.value = raw;
    });

    el.addEventListener('blur', function () {
        let raw = this.value.replace(/\D/g, '');
        if (raw) this.value = raw + ' KG';
    });

    el.addEventListener('focus', function () {
        this.value = this.value.replace(/\s?KG/i, '');
    });
}

/* ⭐ INI YANG KAMU TAMBAH */
function initFormatInputs() {
    const priceEl = document.getElementById('price');
    const weightEl = document.getElementById('weight_kg');

    formatRupiahInput(priceEl);
    formatWeightInput(weightEl);
}

window.formatRupiahInput = formatRupiahInput;
window.formatWeightInput = formatWeightInput;
window.initFormatInputs = initFormatInputs;
