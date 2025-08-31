@props(['title', 'itemId', 'content' => null])

<div class="mb-2 section-card b-left">
    <div class="d-flex justify-content-between align-items-center">
        <h4 class="mb-0 fw-bold">{{ $title }}</h4>

        <div class="d-flex gap-2">
            <x-web.actions :type="'topic'" :item="$itemId" />
        </div>
    </div>

    @if ($content)
        <p class="mb-0">{!! $content !!}</p>
    @endif

    {{ $slot }}
</div>
