    <style>
        .calendar-modal {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .35);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .calendar-box {
            width: 390px;
            background: #fff;
            border-radius: 4px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, .2);
        }

        .calendar-main {
            display: flex;
            flex-direction: column;
        }

        /* HEADER */

        .calendar-header {
            height: 45px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 12px;
            border-bottom: 1px solid #ddd;
            font-size: 15px;
            font-weight: 600;
        }

        .calendar-header button {
            border: none;
            background: none;
            font-size: 20px;
            cursor: pointer;
        }

        /* NAV */

        .calendar-nav {
            height: 42px;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            border-bottom: 1px solid #ddd;
        }

        .nav-btn {
            width: 18px;
            height: 18px;
            border: 1px solid #1F7D53;
            background: #fff;
            color: #1F7D53;
            border-radius: 2px;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 0;
            cursor: pointer;
        }

        #monthSelect,
        #yearSelect {
            border: none;
            background: #fff;
            font-size: 14px;
            outline: none;
            cursor: pointer;
        }

        /* LIST */

        .calendar-sidebar {
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 12px 0;
        }

        .preset {
            height: 38px;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            font-size: 14px;
            transition: .2s;
        }

        .preset:hover {
            background: #f5f5f5;
        }

        .preset.active {
            background: #1F7D53;
            color: #fff;
        }

        /* FOOTER */

        .calendar-footer {
            height: 40px;
            border-top: 1px solid #ddd;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 10px;
            padding: 0 10px;
        }

        .calendar-footer .btn {
            min-width: 70px;
            height: 28px;
            font-size: 12px;
            border-radius: 4px;
        }

        /* MOBILE */

        @media(max-width:576px) {

            .calendar-box {
                width: 95%;
            }

        }
    </style>

    <!-- MODAL CALENDAR -->
    <div id="calendarModal" class="calendar-modal d-none">
        <div class="calendar-box">

            <!-- LEFT PRESET -->


            <!-- RIGHT CALENDAR -->
            <div class="calendar-main">

                <div class="calendar-header">
                    <span>Pilih Bulan dan Tahun</span>
                    <button id="closeCalendar">&times;</button>
                </div>

                <div class="calendar-nav">

                    <button id="prevMonth" class="nav-btn">◀</button>

                    <select id="monthSelect"></select>

                    <select id="yearSelect"></select>

                    <button id="nextMonth" class="nav-btn">▶</button>

                </div>
                <div class="calendar-sidebar">
                    <div class="preset" data-type="thisWeek">Minggu ini</div>
                    <div class="preset" data-type="lastWeek">Minggu Kemarin</div>
                    <div class="preset" data-type="thisMonth">Bulan ini</div>
                    <div class="preset" data-type="lastMonth">Bulan Kemarin</div>
                    <div class="preset" data-type="thisYear">Tahun ini</div>
                    <div class="preset" data-type="lastYear">Tahun Kemarin</div>
                </div>
                {{-- <div class="calendar-weekdays">
                    <div>Sen</div>
                    <div>Sel</div>
                    <div>Rab</div>
                    <div>Kam</div>
                    <div>Jum</div>
                    <div>Sab</div>
                    <div>Min</div>
                </div> --}}

                {{-- <div class="calendar-days" id="calendarDays"></div> --}}

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

            const monthYear = document.getElementById('monthYear');

            const monthSelect = document.getElementById('monthSelect');
            const yearSelect = document.getElementById('yearSelect');

            const presets = document.querySelectorAll('.preset');

            let selectedType = "month";
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

                selectedType = "month";

                startDate = new Date(
                    parseInt(yearSelect.value),
                    parseInt(monthSelect.value),
                    1
                );

                endDate = new Date(
                    parseInt(yearSelect.value),
                    parseInt(monthSelect.value) + 1,
                    0
                );

            };

            yearSelect.onchange = () => {

                selectedType = "month";

                populateMonths(parseInt(yearSelect.value));

                startDate = new Date(
                    parseInt(yearSelect.value),
                    parseInt(monthSelect.value),
                    1
                );

                endDate = new Date(
                    parseInt(yearSelect.value),
                    parseInt(monthSelect.value) + 1,
                    0
                );

            };


            // =========================
            // PRESET CLICK
            // =========================
            presets.forEach(el => {
                el.addEventListener('click', () => {

                    presets.forEach(p => p.classList.remove('active'));
                    el.classList.add('active');
                    selectedType = el.dataset.type;

                    const now = new Date();
                    let start, end;

                    switch (el.dataset.type) {

                        case 'thisYear':
                            start = new Date(now.getFullYear(), 0, 1);
                            end = new Date();
                            break;

                        case 'lastYear':
                            start = new Date(now.getFullYear() - 1, 0, 1);
                            end = new Date(now.getFullYear() - 1, 11, 31);
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

                    startDate = start;
                    endDate = end;

                    monthSelect.value = start.getMonth();
                    yearSelect.value = start.getFullYear();
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
                    const d = new Date(date.getFullYear(), date.getMonth(), date.getDate());

                    const y = d.getFullYear();
                    const m = String(d.getMonth() + 1).padStart(2, '0');
                    const day = String(d.getDate()).padStart(2, '0');

                    return `${y}-${m}-${day}`;
                };

                const start = formatDate(startDate);
                const end = endDate ? formatDate(endDate) : null;

                let chartType = "month";

                if (selectedType === "thisYear" || selectedType === "lastYear") {
                    chartType = "year";
                }

                if (selectedType === "thisWeek" || selectedType === "lastWeek") {
                    chartType = "week";
                }

                document.dispatchEvent(new CustomEvent("dateRangeSelected", {

                    detail: {

                        type: chartType,

                        year: startDate.getFullYear(),

                        month: startDate.getMonth() + 1,

                        start: start,

                        end: end

                    }

                }));

                modal.classList.add('d-none');
            };

            // OPEN MODAL
            openBtn.onclick = () => {

                modal.classList.remove("d-none");

                populateMonths(currentYear);

                monthSelect.value = new Date().getMonth();
                yearSelect.value = currentYear;

                monthSelect.onchange();

            };

            // CLOSE
            closeBtn.onclick = () => modal.classList.add('d-none');
            btnClose.onclick = () => modal.classList.add('d-none');

            // NAVIGATION
            const prev = document.getElementById("prevMonth");
            const next = document.getElementById("nextMonth");

            prev.onclick = () => {

                if (monthSelect.selectedIndex > 0) {

                    monthSelect.selectedIndex--;

                } else {

                    yearSelect.selectedIndex++;

                    populateMonths(parseInt(yearSelect.value));

                    monthSelect.selectedIndex = monthSelect.options.length - 1;
                }

                monthSelect.onchange();

            };

            next.onclick = () => {

                if (monthSelect.selectedIndex < monthSelect.options.length - 1) {

                    monthSelect.selectedIndex++;

                } else {

                    yearSelect.selectedIndex--;

                    populateMonths(parseInt(yearSelect.value));

                    monthSelect.selectedIndex = 0;
                }

                monthSelect.onchange();

            };

        });
    </script>
