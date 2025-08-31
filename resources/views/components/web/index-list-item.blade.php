@props(['href' => '#', 'icon' => "bi bi-play-fill"])

<li {{ $attributes->merge(['class' => 'mb-1']) }}>
    <a href="{{ $href }}" class="d-flex align-items-center justify-content-between w-100">
        <span>{{ $slot }}</span>
        @if ($icon)
            <i class="{{ $icon }}"></i>
        @endif
    </a>
</li>
