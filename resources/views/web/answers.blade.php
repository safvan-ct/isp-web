@extends('layouts.web')

@section('title', $question->translation?->title ?? $question->slug)

@section('content')
    <x-web.page-header :title="$question->position . ' - ' . $question->translation?->title ?? $question->slug" :subtitle="$question->translation?->sub_title" :breadcrumbs="[
        ['label' => __('app.home'), 'url' => route('home')],
        [
            'label' => $question->parent->translation?->title ?? $question->parent->slug,
            'url' => route('questions.show', ['menu_slug' => $menuSlug, 'module_slug' => $moduleSlug]),
        ],
        ['label' => $question->translation?->title ?? $question->slug],
    ]" />

    <x-web.container class="notranslate">
        <div class="row g-2">
            <div class="col-md-9">
                @foreach ($question->children as $item)
                    <x-web.answer-card :title="$item->translation?->title ?? $item->slug" :itemId="$item->id" :content="$item->translation?->content">

                        @if ($item->quranVerses->isNotEmpty() || $item->hadithVerses->isNotEmpty() || $item->videos->isNotEmpty())
                            <ul class="nav nav-tabs" id="addOn-{{ $item->id }}" role="tablist">
                                @if ($item->quranVerses->isNotEmpty())
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link {{ $loop->first ? 'active' : '' }}" id="quran-tab-{{ $item->id }}" data-bs-toggle="tab"
                                            data-bs-target="#quran{{ $item->id }}" type="button" role="tab"
                                            aria-controls="quran{{ $item->id }}" aria-selected="true">
                                            {{ __('app.quran') }}
                                        </button>
                                    </li>
                                @endif

                                @if ($item->hadithVerses->isNotEmpty())
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="hadith-tab-{{ $item->id }}" data-bs-toggle="tab"
                                            data-bs-target="#hadith{{ $item->id }}" type="button" role="tab"
                                            aria-controls="hadith{{ $item->id }}" aria-selected="false">
                                            {{ __('app.hadiths') }}
                                        </button>
                                    </li>
                                @endif

                                @if ($item->videos->isNotEmpty())
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="video-tab-{{ $item->id }}" data-bs-toggle="tab"
                                            data-bs-target="#video{{ $item->id }}" type="button" role="tab"
                                            aria-controls="video{{ $item->id }}" aria-selected="false">
                                            {{ __('app.videos') }}
                                        </button>
                                    </li>
                                @endif
                            </ul>

                            <div class="tab-content" id="addOnContent-{{ $item->id }}">
                                {{-- Quran --}}
                                @if ($item->quranVerses->isNotEmpty())
                                    <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                                        id="quran{{ $item->id }}" role="tabpanel"
                                        aria-labelledby="quran-tab-{{ $item->id }}">
                                        @foreach ($item->quranVerses as $quranVerse)
                                            @php
                                                $verse = $quranVerse->quran;
                                                $chapter = $quranVerse->quran->chapter;
                                                $json = is_array($quranVerse->translation_json)
                                                    ? $quranVerse->translation_json
                                                    : json_decode($quranVerse->translation_json, true);
                                                $ref =
                                                    $chapter->id .
                                                    '.' .
                                                    ($chapter->translation?->name ?? $chapter->name) .
                                                    ': ' .
                                                    $quranVerse->quran->number_in_chapter;
                                            @endphp

                                            <x-web.blockquote type="quran" :id="$quranVerse->quran_verse_id" :text="$quranVerse->simplified"
                                                :translation="$json['ml']" :reference="$ref" />
                                        @endforeach
                                    </div>
                                @endif

                                {{-- Hadith --}}
                                @if ($item->hadithVerses->isNotEmpty())
                                    <div class="tab-pane fade" id="hadith{{ $item->id }}" role="tabpanel"
                                        aria-labelledby="hadith-tab-{{ $item->id }}">
                                        @foreach ($item->hadithVerses as $hadithVerse)
                                            @php
                                                $book = $hadithVerse->hadith->chapter->book;
                                                $json = is_array($hadithVerse->translation_json)
                                                    ? $hadithVerse->translation_json
                                                    : json_decode($hadithVerse->translation_json, true);
                                                $ref =
                                                    ($book->translation?->name ?? $book->name) .
                                                    ': ' .
                                                    $hadithVerse->hadith->hadith_number;
                                            @endphp

                                            <x-web.blockquote type="hadith" :id="$hadithVerse->hadith_verse_id" :text="$hadithVerse->simplified"
                                                :translation="$json['ml']" :reference="$ref" />
                                        @endforeach
                                    </div>
                                @endif

                                {{-- Video --}}
                                @if ($item->videos->isNotEmpty())
                                    <div class="tab-pane fade" id="video{{ $item->id }}" role="tabpanel"
                                        aria-labelledby="video-tab-{{ $item->id }}">
                                        <div class="d-flex flex-wrap justify-content-center gap-2 mt-3">
                                            @foreach ($item->videos as $video)
                                                @php
                                                    $json = json_decode($video->title, true);
                                                    $title = empty($json['ml'])
                                                        ? $question->translation?->title ?? $question->slug
                                                        : $json['ml'];
                                                @endphp

                                                <button class="btn btn-outline-primary"
                                                    onclick="openAddOnModal('video', '{{ $video->video_id }}', '{{ $title }}')">
                                                    🎥 {{ $title }}
                                                </button>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </x-web.answer-card>
                @endforeach

                <x-web.share-box />
            </div>

            <div class="col-md-3">
                <h4 class="text-center fw-bold">{{ __('app.related_questions') }}</h4>
                @foreach ($module->children as $item)
                    @continue($item->id == $question->id)
                    <a class="section-card mb-2 d-flex justify-content-between align-items-center"
                        style="font-size: 14px; padding: 8px;"
                        href="{{ route('answers.show', ['menu_slug' => $module->parent->slug, 'module_slug' => $module->slug, 'question_slug' => $item->slug]) }}">
                        <span>
                            <span class="badge bg-primary">{{ $item->position }}</span> -
                            {{ $item->translation?->title ?? $item->slug }}
                        </span>
                        <span>
                            <i class="bi bi-arrow-right-circle-fill fs-5"></i>
                        </span>
                    </a>
                @endforeach
            </div>
        </div>
    </x-web.container>

    <!-- Add-on Modal -->
    <div class="modal fade" id="addOnModal" tabindex="-1" aria-labelledby="addOnModalLabel">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title notranslate" id="addOnModalLabel"></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div id="google_translate_element" class="mt-2 mb-2 text-center d-none"></div>

                <div class="modal-body p-0" id="addOnModalBody">
                    <div class="text-center text-muted">{{ __('app.loading') }}...</div>
                </div>
            </div>
        </div>
    </div>
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
            updateAllLikeIcon('quran');
            updateAllBookmarkIcon('quran');

            updateAllLikeIcon('hadith');
            updateAllBookmarkIcon('hadith');

            updateAllLikeIcon('topic');
            updateAllBookmarkIcon('topic');
        });

        function openAddOnModal(type, id, title = '') {
            var $modal = $('#addOnModal');
            var $modalLabel = $('#addOnModalLabel');
            var $modalBody = $('#addOnModalBody');
            var $googleTrans = $('#google_translate_element');

            $modalBody.removeAttr('style');

            var addOnModalLabel = '';
            var url = '';

            $googleTrans.addClass('d-none');

            if (type === 'quran') {
                addOnModalLabel = "📖 {{ __('app.quran_revelation') }}";
                url = "{{ route('fetch.quran.verse', ':id') }}".replace(':id', id);
            } else if (type === 'hadith') {
                addOnModalLabel = "📜 {{ __('app.hadith_details') }}";
                url = "{{ route('fetch.hadith.verse', ':id') }}".replace(':id', id);
                $googleTrans.removeClass('d-none');
            } else if (type === 'video') {
                addOnModalLabel = `🎥 ${title}`;
            } else {
                return;
            }

            $modalLabel.html(addOnModalLabel);
            $modalBody.html(`<div class="text-center text-muted py-3">{{ __('app.loading') }}...</div>`);

            $modal.modal('show');

            if (type === 'quran' || type === 'hadith') {
                $.get(url, function(result) {
                    if (!result.html) {
                        $modalBody.html(
                            `<div class="text-danger text-center py-3">{{ __('app.not_found') }}</div>`);
                        return;
                    }
                    $modalBody.html(result.html);

                    $('.ar-number').each(function() {
                        var number = $(this).text().trim();
                        $(this).text(toArabicNumber(number));
                    });

                    if (type === 'quran') {
                        $modal.one('hidden.bs.modal', function() {
                            if (typeof currentAudio !== 'undefined' && currentAudio) {
                                currentAudio.pause();
                            }
                        });
                    }
                }).fail(function() {
                    $modalBody.html(`<div class="text-danger text-center py-3">{{ __('app.not_found') }}</div>`);
                });

            } else {
                $modalBody.css('margin', '-1px').html(`
                    <div class="ratio ratio-16x9">
                        <iframe id="videoIframe" src="https://www.youtube.com/embed/${id}?autoplay=1&mute=0&modestbranding=1&rel=0" title="${addOnModalLabel}" allowfullscreen allow="autoplay"></iframe>
                    </div>
                `);

                $modal.one('hidden.bs.modal', function() {
                    $('#videoIframe').attr('src', '');
                });
            }
        }
    </script>
@endpush
