
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
            ? "weui:eyes-off-outlined"
            : "weui:eyes-on-filled"
        }"></span>
    `;
});

