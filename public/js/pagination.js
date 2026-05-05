function renderPagination(meta) {
    const container = document.querySelector('.custom-pagination');

    let html = '';

    const current = meta.current_page;
    const last = meta.last_page;

    // PREV
    html += `
        <a href="#" class="nav-text ${!meta.prev_page_url ? 'disabled' : ''}" data-page="prev">
            &lt; Sebelumnya
        </a>
    `;

    // =========================
    // CASE: cuma 1 page
    // =========================
    if (last <= 1) {
        html += `
            <a href="#" class="page-number active" data-page="1">1</a>
        `;
    } else {

        const maxVisible = 5;

        let start = Math.max(1, current - 2);
        let end = Math.min(last, start + maxVisible - 1);

        if (end - start < maxVisible - 1) {
            start = Math.max(1, end - maxVisible + 1);
        }

        // FIRST PAGE + ELLIPSIS
        if (start > 1) {
            html += `<a href="#" class="page-number" data-page="1">1</a>`;

            if (start > 2) {
                html += `<span class="dots">...</span>`;
            }
        }

        // RANGE PAGE
        for (let i = start; i <= end; i++) {
            html += `
                <a href="#"
                    class="page-number ${i === current ? 'active' : ''}"
                    data-page="${i}">
                    ${i}
                </a>
            `;
        }

        // LAST PAGE + ELLIPSIS
        if (end < last) {

            if (end < last - 1) {
                html += `<span class="dots">...</span>`;
            }

            html += `
                <a href="#" class="page-number" data-page="${last}">
                    ${last}
                </a>
            `;
        }
    }

    // NEXT
    html += `
        <a href="#" class="nav-text ${!meta.next_page_url ? 'disabled' : ''}" data-page="next">
            Berikutnya &gt;
        </a>
    `;

    container.innerHTML = html;
}
