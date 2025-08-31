@foreach ($result as $item)
    <div class="mt-1 pb-1 section-card notranslate">
        <h2 class="mb-0">{{ $item->translation?->title ?? $item->slug }}</h2>
        {!! $item->translation?->content ?? '' !!}
        <hr>

        <div class="d-flex justify-content-between align-items-center item-card" data-id="${item.id}" data-type="topic">
            <p class="text-muted small notranslate fst-italic">
                🔖
                {{ $item->parent?->parent?->parent?->translation?->title ?? $item->parent?->parent?->parent?->slug }} >
                {{ $item->parent?->parent?->translation?->title ?? $item->parent?->parent?->slug }} >
                {{ $item->parent?->translation?->title ?? $item->parent?->slug }}
            </p>

            <x-web.actions :type="'topic'" :item="$item->id" :bookmarked="isset($bookmarked) ? $bookmarked : false" :liked="isset($liked) ? $liked : false" />
        </div>
    </div>
@endforeach
