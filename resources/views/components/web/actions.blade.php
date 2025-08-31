@props([
    'type',
    'item',
    'chapter' => null,
    'ayah' => null,
    'liked' => false,
    'bookmarked' => false,
    'playOnly' => false,
])

<div class="d-flex align-items-center mt-1 gap-2 justify-content-end item-card" data-id="{{ $item }}"
    data-type="{{ $type }}">
    @if ($chapter && $ayah)
        <a href="javascript:void(0);" class="play-btn" data-surah="{{ $chapter }}" data-ayah="{{ $ayah }}"
            title="Play">
            <i class="far fa-play-circle fs-4"></i>
        </a>
    @endif

    @if (!$playOnly)
        <a href="javascript:void(0);" class="bookmark-btn" data-id="{{ $item }}" data-type="{{ $type }}"
            title="Bookmark">
            <i class="{{ $bookmarked ? 'fas' : 'far' }} fa-bookmark fs-5"></i>
        </a>

        <a href="javascript:void(0);" class="like-btn" data-id="{{ $item }}" data-type="{{ $type }}"
            title="Like">
            <i class="{{ $liked ? 'fas' : 'far' }} fa-heart fs-4"></i>
        </a>
    @endif
</div>
