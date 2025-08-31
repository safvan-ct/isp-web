<!DOCTYPE html>
<html lang="ml">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title class="notranslate">@yield('title', __('app.islamic_study_portal'))</title>

    <!-- Favicon for most browsers -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('img/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('img/favicon-16x16.png') }}">
    <link rel="shortcut icon" href="{{ asset('img/favicon.ico') }}" type="image/x-icon">

    <!-- Android Chrome -->
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('img/android-chrome-192x192.png') }}">
    <link rel="icon" type="image/png" sizes="512x512" href="{{ asset('img/android-chrome-512x512.png') }}">

    <!-- Apple Touch Icon -->
    <link rel="apple-touch-icon" href="{{ asset('img/apple-touch-icon.png') }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Malayalam&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Amiri&family=Cairo&family=Noto+Naskh+Arabic&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('web/css/custom.css') }}">

    @stack('styles')
</head>

<body class="d-flex flex-column min-vh-100">
    <div class="loader" id="pageLoader">
        <div class="spinner"></div>
    </div>

    <nav class="navbar navbar-expand-lg sticky-top notranslate">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2 fw-semibold" href="{{ route('home') }}">
                <img src="{{ asset('img/logo.png') }}" alt="Logo" class="logo">
                <div>
                    ISLAMIC<br>
                    <span class="d-block small">STUDY PORTAL</span>
                </div>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                @php
                    $routeName = Route::currentRouteName();
                    $menu_slug = isset($menuSlug) ? $menuSlug : null;
                    $menus = isset($menus) ? $menus : [];
                @endphp

                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link {{ $routeName == 'home' ? 'active' : '' }} text-uppercase"
                            href="{{ route('home') }}">{{ __('app.home') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Str::is('quran.*', $routeName) ? 'active' : '' }} text-uppercase"
                            href="{{ route('quran.index') }}">{{ __('app.quran') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Str::is('hadith.*', $routeName) ? 'active' : '' }} text-uppercase"
                            href="{{ route('hadith.index') }}">{{ __('app.hadith') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Str::is('calendar', $routeName) ? 'active' : '' }} text-uppercase"
                            href="{{ route('calendar') }}">{{ __('app.calendar') }}</a>
                    </li>

                    @foreach ($menus as $item)
                        <li class="nav-item">
                            <a class="nav-link text-uppercase {{ $item->slug == $menu_slug ? 'active' : '' }}"
                                href="{{ route('modules.show', $item->slug) }}">
                                {{ $item->translation?->title ?: $item->slug }}
                            </a>
                        </li>
                    @endforeach

                    @if (Auth::check() && Auth::user()->role == 'Customer')
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-uppercase {{ Str::is('collections.*', $routeName) || Str::is('likes', $routeName) ? 'active' : '' }}"
                                href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                {{ __('app.profile') }}
                            </a>

                            <ul class="dropdown-menu" aria-labelledby="profileDropdown">
                                <li>
                                    <a class="dropdown-item text-uppercase" href="#">{{ __('app.account') }}</a>
                                </li>
                                <li>
                                    <a class="dropdown-item text-uppercase" href="{{ route('likes') }}">
                                        {{ __('app.likes') }}
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item text-uppercase" href="{{ route('collections.index') }}">
                                        {{ __('app.bookmarks') }}
                                    </a>
                                </li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf

                                        <a href="javascript:void(0);" class="dropdown-item text-uppercase"
                                            onclick="event.preventDefault(); this.closest('form').submit();">
                                            {{ __('app.logout') }}
                                        </a>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li>
                            <a class="nav-link {{ Str::is('likes', $routeName) ? 'active' : '' }} text-uppercase"
                                href="{{ route('likes') }}">
                                {{ __('app.likes') }}
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ Str::is('login', $routeName) || Str::is('register', $routeName) ? 'active' : '' }} text-uppercase"
                                href="{{ route('login') }}">
                                {{ __('app.login') }}
                            </a>
                        </li>
                    @endif

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-uppercase" href="#" id="languageDropdown"
                            role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ app()->getLocale() }}
                        </a>

                        <ul class="dropdown-menu" aria-labelledby="languageDropdown">
                            @foreach (array_keys(config('app.languages')) as $code)
                                <li>
                                    <a class="dropdown-item text-uppercase"
                                        href="{{ route('change.language', $code) }}">
                                        {{ $code }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    @yield('content')

    <!-- Save to Collection Modal -->
    <div class="modal fade" id="collectionModal" tabindex="-1" aria-labelledby="collectionModalLabel"
        aria-hidden="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="collectionModalLabel">Save to Collection</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <h6>Choose existing collection:</h6>
                    <ul id="collectionList" class="list-group mb-3"></ul>

                    <hr>
                    <h6>Create new collection:</h6>
                    <div class="input-group mt-2">
                        <input type="text" id="newCollectionName" class="form-control"
                            placeholder="New collection name">
                        <button id="createCollectionBtn" class="btn btn-primary">
                            Create & Save
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="notranslate">
        © {{ date('Y') }} Islamic Life &nbsp;|&nbsp; All Rights Reserved &nbsp;|&nbsp;

        <a href="https://www.instagram.com/islamicstudyportal" target="_blank"
            class="text-white text-decoration-none">
            <i class="bi bi-instagram text-white"></i>
        </a>
        &nbsp;|&nbsp;
        <a href="https://www.youtube.com/@islamic_study_portal" target="_blank"
            class="text-white text-decoration-none">
            <i class="bi bi-youtube text-white"></i>
        </a>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script src="{{ asset('web/js/custom.js') }}"></script>
    <script src="{{ asset('web/js/like-bookmark.js') }}"></script>

    @php
        $quranLikedIds = $hadithLikedIds = $topicsLikedIds = [];
        $quranBookmarkedIds = $hadithBookmarkedIds = $topicsBookmarkedIds = [];

        if (auth()->check() && auth()->user()->role == 'Customer') {
            $likes = auth()
                ->user()
                ->likes()
                ->whereIn('likeable_type', ['App\Models\QuranVerse', 'App\Models\HadithVerse', 'App\Models\Topic'])
                ->get(['likeable_type', 'likeable_id'])
                ->groupBy('likeable_type');

            $quranLikedIds = $likes->get('App\Models\QuranVerse', collect())->pluck('likeable_id')->toArray();
            $hadithLikedIds = $likes->get('App\Models\HadithVerse', collect())->pluck('likeable_id')->toArray();
            $topicsLikedIds = $likes->get('App\Models\Topic', collect())->pluck('likeable_id')->toArray();

            $bookmarks = auth()
                ->user()
                ->bookmarks()
                ->whereIn('bookmarkable_type', ['App\Models\QuranVerse', 'App\Models\HadithVerse', 'App\Models\Topic'])
                ->get(['bookmarkable_type', 'bookmarkable_id'])
                ->groupBy('bookmarkable_type');

            $quranBookmarkedIds = $bookmarks
                ->get('App\Models\QuranVerse', collect())
                ->pluck('bookmarkable_id')
                ->unique()
                ->values()
                ->toArray();
            $hadithBookmarkedIds = $bookmarks
                ->get('App\Models\HadithVerse', collect())
                ->pluck('bookmarkable_id')
                ->unique()
                ->values()
                ->toArray();
            $topicsBookmarkedIds = $bookmarks
                ->get('App\Models\Topic', collect())
                ->pluck('bookmarkable_id')
                ->unique()
                ->values()
                ->toArray();
        }
    @endphp

    <script>
        window.AUTH_USER = "{{ auth()->check() && auth()->user()->role == 'Customer' }}";

        //------------------------
        // Like functionality
        //------------------------
        window.LIKE_URL = AUTH_USER ? "{{ route('like.toggle') }}" : null;

        function updateAllLikeIcon(type) {
            let likedIds = [];
            if (AUTH_USER) {
                if (type === 'quran') {
                    likedIds = @json($quranLikedIds);
                } else if (type === 'hadith') {
                    likedIds = @json($hadithLikedIds);
                } else if (type === 'topic') {
                    likedIds = @json($topicsLikedIds);
                }
            } else {
                const likes = JSON.parse(localStorage.getItem("likes") || "{}");
                likedIds = likes[type] || [];
            }

            likedIds.forEach(function(id) {
                updateLikeIconState(type, id, true);
            })
        }

        //------------------------
        // Bookmark functionality
        //------------------------
        window.COLLECTION_FETCH_URL = AUTH_USER ? "{{ route('fetch.collections') }}" : null;
        window.BOOKMARK_URL = AUTH_USER ? "{{ route('bookmark.toggle') }}" : null;
        window.COLLECTION_URL = AUTH_USER ? "{{ route('collections.store') }}" : null;

        function updateAllBookmarkIcon(type) {
            let bookmarkedIds = [];
            if (AUTH_USER) {
                if (type === 'quran') {
                    bookmarkedIds = @json($quranBookmarkedIds);
                } else if (type === 'hadith') {
                    bookmarkedIds = @json($hadithBookmarkedIds);
                } else if (type === 'topic') {
                    bookmarkedIds = @json($topicsBookmarkedIds);
                }
            } else {
                const bookmarks = JSON.parse(localStorage.getItem("ISPBOOKMARKS") || "{}");
                bookmarkedIds = bookmarks[type] || [];
            }

            bookmarkedIds.forEach(function(id) {
                updateBookmarkIconState(type, id, true);
            })
        }

        $(function() {
            CollectionList = [];
            $('#pageLoader').addClass('d-none');

            // Toggle like on click
            $(document).on('click', '.like-btn', function() {
                let type = $(this).data('type');
                let id = $(this).data('id');
                toggleLike(type, id);
            });

            // Play audio
            $(document).on('click', '.play-btn', function() {
                playAudio.call(this);
            });

            // Collection modal close
            $("#collectionModal").one("hidden.bs.modal", function() {
                CollectionList = [];
                $("#newCollectionName").val('');
            });

            // Sync likes
            @if (auth()->check() && session('sync_data'))
                let likes = JSON.parse(localStorage.getItem('likes') || '{}');

                $.ajax({
                    url: "{{ route('likes.sync') }}",
                    type: "POST",
                    data: JSON.stringify({
                        likes: Object.entries(likes).flatMap(([type, ids]) =>
                            ids.map(id => ({
                                id,
                                type
                            }))
                        ),
                    }),
                    contentType: "application/json",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    success: function(data) {
                        updateAllLikeIcon('quran');
                        updateAllLikeIcon('hadith');
                        updateAllLikeIcon('topic');

                        localStorage.removeItem("likes");
                        localStorage.removeItem("ISPBOOKMARKS");
                    },
                    error: function(xhr) {
                        console.error("Sync failed:", xhr.responseText);
                    }
                });
            @endif
        });
    </script>

    @stack('scripts')
</body>

</html>
