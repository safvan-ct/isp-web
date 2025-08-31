@props(['name' => null, 'writer' => null])

<div {{ $attributes->merge(['class' => 'chapter-header mb-2']) }}>
    @if ($name)
        <div class="chapter-name">
            {!! $name !!}
        </div>
    @endif

    @if ($writer)
        <div class="notranslate">
            {{ $writer }}
        </div>
    @endif

    {{ $slot }}
</div>
