@props(['book', 'volume', 'chapter', 'hadith', 'status'])

<p class="text-muted small notranslate fst-italic">
    🔖 {{ $book }},
    {{ __('app.volume') }}: {{ $volume }},
    {{ __('app.chapter') }}: #{{ $chapter }},
    {{ __('app.hadith') }}: #{{ $hadith }},
    {{ __('app.status') }}: {{ __('app.' . strtolower($status)) }}
</p>
