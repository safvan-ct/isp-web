@extends('layouts.web')

@section('title', __('app.likes'))

@section('content')
    <x-web.container>
        <x-web.index-card class="b-top">
            <x-web.chapter-header :name="'<i class=\'fas fa-heart\'></i> ' . e(__('app.likes'))">
                <x-web.nav-tab />
                <div id="google_translate_element" class="mt-2 mb-0 d-none"></div>
            </x-web.chapter-header>

            <div id="result-container">
                <p class="text-center notranslate">{{ __('app.loading') }}...</p>
            </div>

            <span id="pagination-container"></span>
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
            fetchLikeOrBookmark('quran');
        });

        const $container = $('#result-container');
        const $pagination = $('#pagination-container');
        const $googleTranslate = $('#google_translate_element');

        const urlMap = {
            quran: "{{ route('fetch.quran.like') }}",
            hadith: "{{ route('fetch.hadith.like') }}",
            topic: "{{ route('fetch.topic.like') }}"
        };

        async function fetchLikeOrBookmark(type, page = 1) {
            toggleLoader(true);
            resetUI(type);

            const likes = JSON.parse(localStorage.getItem('likes') || '{}')[type] || [];
            if (!AUTH_USER && likes.length === 0) {
                showMessage("{{ __('app.no_likes_found') }}");
                toggleLoader(false);
                return;
            }

            if (!urlMap[type]) {
                showMessage(`Invalid type: ${type}`);
                toggleLoader(false);
                return;
            }

            try {
                const res = await $.ajax({
                    url: urlMap[type],
                    method: 'POST',
                    contentType: 'application/json',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: JSON.stringify({
                        ids: likes,
                        page
                    }),
                    dataType: 'json'
                });

                await renderResult(type, res);
                updateAllBookmarkIcon(type);
            } catch (error) {
                console.error(error);
                showMessage("Error loading likes.");
            } finally {
                toggleLoader(false);
            }
        }

        function renderResult(type, response) {
            if (!response.html?.length) {
                showMessage("{{ __('app.no_likes_found') }}");
                return;
            }

            $container.html(response.html);
            $pagination.html(response.pagination).show();

            // Bind page clicks
            $pagination.find('a.page-link').off('click').on('click', function() {
                const page = $(this).data('page');
                if (page) fetchLikeOrBookmark(type, page);
            });

            $('.ar-number').each(function() {
                const number = $(this).text().trim();
                $(this).text(toArabicNumber(number));
            });
        }

        function resetUI(type) {
            $pagination.empty().hide();
            $('.tabs').removeClass('active');
            $(`#${type}-tab`).addClass('active');
            $googleTranslate.toggleClass('d-none', type !== 'hadith');
        }

        function showMessage(message) {
            $container.html(`<p class='text-center'>${message}</p>`);
        }
    </script>
@endpush
