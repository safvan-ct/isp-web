@props(['title' => null, 'subTitle' => null, 'dot' => true])

<div class="card h-100 justify-content-center">
    <div class="d-flex align-items-center gap-2">
        @if ($dot)
            <span class="accent-dot"></span>
        @endif

        @if ($title || $subTitle)
            <div class="text-center">
                @if ($title)
                    <div class="fw-bold">{{ $title }}</div>
                @endif

                @if ($subTitle)
                    <small class="text-secondary">{{ $subTitle }}</small>
                @endif
            </div>
        @endif
    </div>
</div>
