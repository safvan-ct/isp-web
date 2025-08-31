@props(['icon' => null, 'title' => null, 'description' => null, 'href' => null, 'btnText' => null])

<article class="card-surface feature-card h-100 rounded-4 p-3">
    @if ($icon)
        <div class="icon-card rounded-5 mb-3"><span class="fs-3">{{ $icon }}</span></div>
    @endif

    @if ($title)
        <h5 class=" mb-1">{{ $title }}</h5>
    @endif

    @if ($description)
        <p class="mb-3 text-secondary">{{ $description }}</p>
    @endif

    @if ($href)
        <a href="{{ $href }}" class="btn btn-sm btn-outline-success rounded-pill">{{ $btnText ?? 'Open' }}</a>
    @endif
</article>
