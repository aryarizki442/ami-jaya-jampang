// +++++++++++++++++++++++++++++++++
// Script Untuk Button Aktif
document.addEventListener('DOMContentLoaded', function () {

    const buttons = document.querySelectorAll('[data-btn-target]');

    buttons.forEach(btn => {
        const inputIds = btn.getAttribute('data-btn-target').split(',');

        const inputs = inputIds.map(id => document.getElementById(id.trim()));

        function checkInputs() {
            const allFilled = inputs.every(input => input && input.value.trim() !== '');

            if (allFilled) {
                btn.classList.add('active'); // hanya ubah warna
            } else {
                btn.classList.remove('active'); // kembali default
            }
        }

        // event input
        inputs.forEach(input => {
            if (input) {
                input.addEventListener('input', checkInputs);
            }
        });

        // initial check
        checkInputs();
    });

});

// +++++++++++++++++++++++++++++++++
//Script Untuk Toggle Password (Mata)
document.addEventListener("click", function (e) {
    const toggle = e.target.closest(".password-toggle");
    if (!toggle) return;

    const input = document.getElementById(toggle.dataset.target);
    const isHidden = input.type === "password";

    // toggle input type
    input.type = isHidden ? "text" : "password";

    // update icon setelah klik
    toggle.innerHTML = `
        <span class="iconify" data-icon="${isHidden
            ? "weui:eyes-on-filled"
            : "weui:eyes-off-outlined"
        }"></span>
    `;
});

// saat user mengetik password
// document.addEventListener("input", function (e) {
//     if (e.target.type !== "password" && e.target.type !== "text") return;

//     const wrapper = e.target.closest(".password-wrapper");
//     if (!wrapper) return;

//     const toggle = wrapper.querySelector(".password-toggle");
//     if (!toggle) return;

//     // kalau ada isi, icon jadi off-outlined
//     if (e.target.value.length > 0) {
//         toggle.innerHTML = `
//             <span class="iconify" data-icon="weui:eyes-off-outlined"></span>
//         `;
//     } else {
//         toggle.innerHTML = `
//             <span class="iconify" data-icon="weui:eyes-on-filled"></span>
//         `;
//     }
// });
