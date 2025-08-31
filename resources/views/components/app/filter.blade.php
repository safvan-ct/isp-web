<div class="position-sticky z-3" style="top: 72px;">
    <div id="filterContent" class="collapse d-md-block mt-3">
        {{ $slot }}
    </div>

    <div class="d-flex justify-content-end d-md-none">
        <button id="filterFab" class="btn btn-success filter-fab d-md-none rounded-circle mt-2" type="button"
            data-bs-toggle="collapse" data-bs-target="#filterContent" aria-expanded="false" aria-controls="filterContent">
            <i class="fas fa-filter"></i>
        </button>
    </div>
</div>
