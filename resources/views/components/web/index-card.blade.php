@props(['title' => null, 'description' => null])

<div {{ $attributes->merge(['class' => 'index-card']) }}>
    @if ($title)
        <h3 class="text-center text-quran">{{ $title }}</h3>
    @endif

    @if ($description)
        <p class="text-center">{{ $description }}</p>
    @endif

    {{ $slot }}
</div>
