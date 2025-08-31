@props(['icon' => null, 'title' => null, 'description' => null, 'href' => '#', 'btnText' => null])

<div class="card section-card h-100 text-center d-flex flex-column justify-content-between">
    <div class="card-body">
        <h5 class="card-title">{!! $icon !!} {{ $title }}</h5>
        <p class="card-text">{{ $description }}</p>
    </div>

    <div class="card-footer bg-transparent border-0 pb-0">
        <a href="{{ $href }}" class="btn btn-primary">{{ $btnText }}</a>
    </div>
</div>
