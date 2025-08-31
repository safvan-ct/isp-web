@props(['id' => 'modal', 'title' => 'Create', 'size' => ''])

<div class="modal fade createUpdate" id="{{ $id }}" tabindex="-1" aria-labelledby="{{ $id }}Label"
    aria-hidden="true">
    <div class="modal-dialog {{ $size }}">
        <div class="modal-content">
            @if (!empty($title))
                <div class="modal-header">
                    <h5 class="modal-title">{{ $title }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            @endif

            <div class="modal-body">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>
