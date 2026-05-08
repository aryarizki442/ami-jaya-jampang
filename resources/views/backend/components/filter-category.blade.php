<div class="col-6 col-md-auto position-relative">

    <!-- BUTTON TRIGGER -->
    <button type="button" id="filterBtn"
        class="btn btn-filter-admin w-100 d-flex align-items-center justify-content-center gap-1">
        <span class="iconify" data-icon="mingcute:filter-line"></span>
        <span class="label">Filter</span>
    </button>

    <!-- DROPDOWN -->
    <div id="filterDropdown" class="position-absolute bg-white shadow rounded mt-2 d-none"
        style="min-width:220px; z-index:999;">

        <!-- HEADER (SELECTED LABEL) -->
        <div class="px-3 py-2 border-bottom d-flex justify-content-between align-items-center">
            <span id="selectedCategoryLabel">Semua Kategori</span>
            <span class="iconify" data-icon="iconamoon:arrow-down-2-light"></span>
        </div>

        <!-- LIST -->
        <div id="categoryFilterList" class="d-flex flex-column p-2 gap-1">

            <button class="btn btn-sm text-start btn-dropdown-filter-all" data-id="">
                Semua Kategori
            </button>

            <button class="btn btn-sm text-start btn-dropdown-filter-premium" data-id="1">
                Premium
            </button>

            <button class="btn btn-sm text-start btn-dropdown-filter-medium" data-id="2">
                Medium
            </button>

            <button class="btn btn-sm text-start btn-dropdown-filter-ketan" data-id="3">
                Ketan
            </button>

        </div>

    </div>
</div>
