    <style>
        .calendar-modal {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        .calendar-box {
            width: 520px;
            /* 🔥 fix lebar */
            min-width: 520px;
            max-width: 520px;

            height: 420px;
            /* 🔥 fix tinggi */
            max-height: 420px;

            background: #fff;
            border-radius: 10px;
            font-size: 14px;

            display: flex;
            overflow: hidden;
            /* biar rapi */
        }

        .calendar-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-weight: 600;
            border-bottom: 1px solid #ddd;
        }

        .calendar-header button {
            border: none;
            background: none;
            font-size: 18px;
        }

        .calendar-nav {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-bottom: 10px;
        }

        .calendar-nav button {
            border: 1px solid #ddd;
            background: #fff;
            padding: 4px 10px;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .calendar-nav button:hover {
            background: #f0f0f0;
        }

        .calendar-nav button:active {
            transform: scale(0.95);
        }

        .calendar-days {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 0;
        }

        .day {
            text-align: center;
            padding: 8px;
            cursor: pointer;
            border-radius: 0;
            transition: all 0.2s ease;
        }

        /* HOVER */
        .day:hover {
            background: #eee;
        }

        /* RANGE TENGAH */
        .day.in-range {
            background: #1F7D53;
            color: #fff;
        }

        /* START */
        .day.start {
            background: #1F7D53;
            color: #fff;
            border-radius: 50px 0 0 50px;
        }

        /* END */
        .day.end {
            background: #1F7D53;
            color: #fff;
            border-radius: 0 50px 50px 0;
        }

        /* 🔥 SINGLE DAY (INI YANG KAMU MAU) */
        .day.single {
            background: #1F7D53;
            color: #fff;
            border-radius: 50%;
        }

        .calendar-footer {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            padding-top: 10px;
            margin-top: auto;
            /* 🔥 ini kuncinya */
        }



        /* Weekdays */
        .calendar-weekdays {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            margin-bottom: 5px;
            font-size: 12px;
            color: #777;
            text-align: center;
        }

        .calendar-weekdays div {
            padding: 5px 0;
            font-weight: 500;
        }

        /* SIDEBAR */
        .calendar-sidebar {
            width: 160px;
            background: #f7f7f7;
            padding: 10px;
            border-right: 1px solid #ddd;
        }

        .preset {
            padding: 8px;
            border-radius: 6px;
            cursor: pointer;
        }

        .preset:hover {
            background: #eaeaea;
        }

        .preset.active {
            background: #1F7D53;
            color: white;
        }

        /* RIGHT */
        .calendar-main {
            flex: 1;
            padding: 15px;

            display: flex;
            /* 🔥 penting */
            flex-direction: column;
            /* 🔥 penting */
        }

        .nav-select {
            display: flex;
            gap: 6px;
            align-items: center;
        }

        .nav-select select {
            border: 1px solid #ddd;
            background: #f9f9f9;
            padding: 5px 10px;
            border-radius: 6px;
            font-size: 13px;
            cursor: pointer;
        }

        .nav-select select:hover {
            background: #f1f1f1;
        }

        @media (max-width: 576px) {
            .calendar-box {
                width: 95%;
                min-width: unset;
                max-width: unset;

                height: auto;
                max-height: 90vh;
                /* biar ga kepotong layar */

                flex-direction: column;
                /* sidebar jadi atas */
            }

            .calendar-sidebar {
                width: 100%;
                display: flex;
                flex-wrap: wrap;
                gap: 5px;
                border-right: none;
                border-bottom: 1px solid #eee;
            }

            .preset {
                flex: 1 1 45%;
                text-align: center;
            }
        }
    </style>

    <!-- MODAL CALENDAR -->
    <div id="calendarModal" class="calendar-modal d-none">
        <div class="calendar-box d-flex">

            <!-- LEFT PRESET -->
            <div class="calendar-sidebar">
                <div class="preset" data-type="today">Hari ini</div>
                <div class="preset" data-type="yesterday">Kemarin</div>
                <div class="preset" data-type="thisWeek">Minggu ini</div>
                <div class="preset" data-type="lastWeek">Minggu kemarin</div>
                <div class="preset" data-type="thisMonth">Bulan ini</div>
                <div class="preset" data-type="lastMonth">Bulan kemarin</div>
            </div>

            <!-- RIGHT CALENDAR -->
            <div class="calendar-main">

                <div class="calendar-header">
                    <span>Pilih Rentang Tanggal</span>
                    <button id="closeCalendar">&times;</button>
                </div>

                <div class="calendar-nav">
                    <button id="prevMonth">‹</button>

                    <div class="nav-select">
                        <select id="monthSelect"></select>
                        <select id="yearSelect"></select>
                    </div>

                    <button id="nextMonth">›</button>
                </div>

                <div class="calendar-weekdays">
                    <div>Sen</div>
                    <div>Sel</div>
                    <div>Rab</div>
                    <div>Kam</div>
                    <div>Jum</div>
                    <div>Sab</div>
                    <div>Min</div>
                </div>

                <div class="calendar-days" id="calendarDays"></div>

                <div class="calendar-footer">
                    <button class="btn btn-second btn-sm" id="btnClose">Tutup</button>
                    <button class="btn btn-main btn-sm" id="btnSave">Simpan</button>
                </div>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const modal = document.getElementById('calendarModal');
            const openBtn = document.getElementById('openCalendar');
            const closeBtn = document.getElementById('closeCalendar');
            const btnClose = document.getElementById('btnClose');
            const btnSave = document.getElementById('btnSave');

            const daysEl = document.getElementById('calendarDays');
            const monthYear = document.getElementById('monthYear');

            const monthSelect = document.getElementById('monthSelect');
            const yearSelect = document.getElementById('yearSelect');

            const presets = document.querySelectorAll('.preset');

            let current = new Date();
            let startDate = null;
            let endDate = null;

            const months = [
                "Januari", "Februari", "Maret", "April", "Mei", "Juni",
                "Juli", "Agustus", "September", "Oktober", "November", "Desember"
            ];

            // isi bulan
            function populateMonths(selectedYear) {
                monthSelect.innerHTML = '';

                const now = new Date();
                const currentYear = now.getFullYear();
                const currentMonth = now.getMonth(); // 0 = Jan

                months.forEach((m, i) => {

                    // 🔥 kalau tahun sekarang → batasi bulan
                    if (selectedYear == currentYear && i > currentMonth) return;

                    const opt = document.createElement('option');
                    opt.value = i;
                    opt.textContent = m;
                    monthSelect.appendChild(opt);
                });
            }

            // isi tahun
            const currentYear = new Date().getFullYear();

            for (let y = currentYear; y >= currentYear - 10; y--) {
                const opt = document.createElement('option');
                opt.value = y;
                opt.textContent = y;
                yearSelect.appendChild(opt);
            }
            monthSelect.onchange = () => {
                current.setMonth(parseInt(monthSelect.value));
                renderCalendar();
            };

            yearSelect.onchange = () => {
                const selectedYear = parseInt(yearSelect.value);

                current.setFullYear(selectedYear);

                // 🔥 update bulan sesuai tahun
                populateMonths(selectedYear);

                // kalau bulan sekarang tidak valid → reset
                if (current.getMonth() > monthSelect.options.length - 1) {
                    current.setMonth(monthSelect.options.length - 1);
                }
                renderCalendar();
            };

            // =========================
            // RENDER CALENDAR
            // =========================
            const isSingle = startDate && !endDate;

            function renderCalendar() {
                daysEl.innerHTML = '';

                const year = current.getFullYear();
                const month = current.getMonth();

                populateMonths(year);
                monthSelect.value = month;
                yearSelect.value = year;

                let firstDay = new Date(year, month, 1).getDay();

                // ubah biar Senin = 0
                firstDay = firstDay === 0 ? 6 : firstDay - 1;
                const lastDate = new Date(year, month + 1, 0).getDate();

                for (let i = 0; i < firstDay; i++) {
                    daysEl.innerHTML += `<div></div>`;
                }

                for (let i = 1; i <= lastDate; i++) {
                    const date = new Date(year, month, i);
                    const div = document.createElement('div');
                    div.classList.add('day');
                    div.textContent = i;

                    if (startDate && endDate && date >= startDate && date <= endDate) {
                        div.classList.add('in-range');
                    }

                    const isSameDay = (a, b) =>
                        a && b && a.getTime() === b.getTime();

                    if (startDate && endDate) {

                        const isSameDay = startDate.getTime() === endDate.getTime();

                        if (isSameDay) {
                            if (date.getTime() === startDate.getTime()) {
                                div.classList.add('single');
                            }
                        } else {
                            if (date >= startDate && date <= endDate) {
                                div.classList.add('in-range');
                            }

                            if (date.getTime() === startDate.getTime()) {
                                div.classList.add('start');
                            }

                            if (date.getTime() === endDate.getTime()) {
                                div.classList.add('end');
                            }
                        }

                    } else if (isSingle && date.getTime() === startDate.getTime()) {
                        div.classList.add('single');
                    }
                    div.addEventListener('click', () => {
                        presets.forEach(p => p.classList.remove('active'));

                        if (!startDate || (startDate && endDate)) {
                            startDate = date;
                            endDate = null;
                        } else {
                            if (date < startDate) {
                                endDate = startDate;
                                startDate = date;
                            } else {
                                endDate = date;
                            }
                        }

                        renderCalendar();
                    });

                    daysEl.appendChild(div);
                }
            }

            // =========================
            // SET RANGE (UNTUK PRESET)
            // =========================
            function setRange(start, end) {
                startDate = new Date(start.setHours(0, 0, 0, 0));
                endDate = new Date(end.setHours(0, 0, 0, 0));
                current = new Date(startDate); // pindah bulan sesuai pilihan
                renderCalendar();
            }

            // =========================
            // PRESET CLICK
            // =========================
            presets.forEach(el => {
                el.addEventListener('click', () => {

                    presets.forEach(p => p.classList.remove('active'));
                    el.classList.add('active');

                    const now = new Date();
                    let start, end;

                    switch (el.dataset.type) {

                        case 'today':
                            start = new Date();
                            end = new Date();
                            break;

                        case 'yesterday':
                            start = new Date();
                            start.setDate(start.getDate() - 1);
                            end = new Date(start);
                            break;

                        case 'thisWeek':
                            start = new Date(now);
                            start.setDate(now.getDate() - now.getDay() + 1);
                            end = new Date(now);
                            break;

                        case 'lastWeek':
                            start = new Date(now);
                            start.setDate(now.getDate() - now.getDay() - 6);
                            end = new Date(now);
                            end.setDate(now.getDate() - now.getDay());
                            break;

                        case 'thisMonth':
                            start = new Date(now.getFullYear(), now.getMonth(), 1);
                            end = new Date();
                            break;

                        case 'lastMonth':
                            start = new Date(now.getFullYear(), now.getMonth() - 1, 1);
                            end = new Date(now.getFullYear(), now.getMonth(), 0);
                            break;
                    }

                    setRange(start, end);
                });
            });

            // BTN SAVE (KIRIM DATA KE INDEX.BLADE)
            // =========================
            // BUTTON ACTION
            // =========================

            // 🔥 SAVE (kirim ke index.blade)
            btnSave.onclick = () => {
                if (!startDate) return;

                const formatDate = (date) => {
                    const y = date.getFullYear();
                    const m = String(date.getMonth() + 1).padStart(2, '0');
                    const d = String(date.getDate()).padStart(2, '0');
                    return `${y}-${m}-${d}`;
                };

                const start = formatDate(startDate);
                const end = endDate ? formatDate(endDate) : null;

                document.dispatchEvent(new CustomEvent('dateRangeSelected', {
                    detail: {
                        start,
                        end
                    }
                }));

                modal.classList.add('d-none');
            };

            // OPEN MODAL
            openBtn.onclick = () => {
                modal.classList.remove('d-none');
                renderCalendar();
            };

            // CLOSE
            closeBtn.onclick = () => modal.classList.add('d-none');
            btnClose.onclick = () => modal.classList.add('d-none');

            // NAVIGATION
            document.getElementById('prevMonth').onclick = () => {
                current.setMonth(current.getMonth() - 1);
                renderCalendar();
            };

            document.getElementById('nextMonth').onclick = () => {
                current.setMonth(current.getMonth() + 1);
                renderCalendar();
            };

        });
    </script>
