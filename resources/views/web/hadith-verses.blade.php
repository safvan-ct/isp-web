@extends('layouts.web')

@section('title', ($chapter->book->translation?->name ?? $chapter->book->name) . ' | ' . ($chapter->translation?->name
    ?? $chapter->name))

@section('content')
    <x-web.container>
        <x-web.index-card>
            <x-web.chapter-header class="notranslate" :name="(optional($chapter->translation)->name ? $chapter->translation->name . ' | ' : '') .
                $chapter->name" :writer="$chapter->book->translation?->name ?? $chapter->book->name">

                @if (!isset($verseNumber))
                    <div class="notranslate">
                        {{ __('app.chapter') }}: <strong class="ar-number">{{ $chapter->chapter_number }}</strong> |
                        {{ __('app.hadiths') }}: <strong class="ar-number">{{ $chapter->verses->count() }}</strong>
                    </div>
                @endif

                <a href="{{ route('hadith.chapters', $chapter->book->id) }}" id="book-url" style="text-decoration: underline">
                    {{ __('app.chapters') }}
                </a>

                @if (isset($verseNumber))
                    | <a href="{{ route('hadith.chapter.verses', ['book' => $chapter->book->slug, 'chapter' => $chapter->id]) }}"
                        style="text-decoration: underline">
                        {{ $chapter->translation?->name ?? $chapter->name }}
                    </a>
                @endif

                <x-web.input-search />

                <div id="google_translate_element" class="mt-2 mb-0"></div>
            </x-web.chapter-header>

            @include('web.partials.hadith-list', [
                'result' => $chapter->verses,
                'book' => $chapter->book,
                'chapter' => $chapter,
            ])
        </x-web.index-card>
    </x-web.container>
@endsection

@push('scripts')
    <script type="text/javascript">
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({
                    pageLanguage: 'en',
                    includedLanguages: 'ml,hi',
                    layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
                    autoDisplay: false
                },
                'google_translate_element'
            );
        }
    </script>
    <script type="text/javascript" src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit">
    </script>

    <script>
        $(function() {
            updateAllLikeIcon('hadith');
            updateAllBookmarkIcon('hadith');

            $('.ar-number').each(function() {
                const number = $(this).text().trim();
                $(this).text(toArabicNumber(number));
            });
        });

        function searchHadithByNumber() {
            const number = parseInt($('#hadith-number').val(), 10);

            if (isNaN(number) || number < 1) {
                alert("Please enter a valid Hadith number.");
                return;
            }

            window.location.href = "{{ route('hadith.book.verse', [$chapter->book->id, ':verse']) }}"
                .replace(':verse', number);
        }
    </script>
@endpush
