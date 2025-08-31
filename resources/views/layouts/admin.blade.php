<!doctype html>
<html lang="en">

<head>
    <title>{{ config('app.name') }}</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" href="{{ asset('img/favicon.ico') }}" type="image/x-icon" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap"
        id="main-font-link" />
    <link rel="stylesheet" href="{{ asset('admin/fonts/phosphor/duotone/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('admin/fonts/tabler-icons.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('admin/fonts/feather.css') }}" />
    <link rel="stylesheet" href="{{ asset('admin/fonts/fontawesome.css') }}" />
    <link rel="stylesheet" href="{{ asset('admin/fonts/material.css') }}" />

    <link rel="stylesheet" href="{{ asset('admin/css/style.css') }}" id="main-style-link" />
    <link rel="stylesheet" href="{{ asset('admin/css/style-preset.css') }}" />
    <link rel="stylesheet" href="{{ asset('admin/css/custom.css') }}" />

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">

    <style>
        .choices__list--dropdown,
        .choices__list[aria-expanded] {
            z-index: 9999 !important;
        }
    </style>

    @stack('styles')
</head>

<body>
    @php
        $menu = isset($menu) ? $menu : null;
        $route = Route::currentRouteName();

        $ICTR = Str::startsWith($route, ['admin.category.', 'admin.category-translation.']) && $menu === 'islam';
        $ITR = Str::startsWith($route, ['admin.topic.', 'admin.topic-translation.']) && $menu === 'islam';
        $ICR = Str::startsWith($route, ['admin.content.', 'admin.content-translation.']) && $menu === 'islam';

        $BCTR = Str::startsWith($route, ['admin.category.', 'admin.category-translation.']) && $menu === 'belief';
        $BTR = Str::startsWith($route, ['admin.topic.', 'admin.topic-translation.']) && $menu === 'belief';
        $BCR = Str::startsWith($route, ['admin.content.', 'admin.content-translation.']) && $menu === 'belief';

        $HCTR = Str::startsWith($route, ['admin.category.', 'admin.category-translation.']) && $menu === 'history';
        $HTR = Str::startsWith($route, ['admin.topic.', 'admin.topic-translation.']) && $menu === 'history';

        $LCTR = Str::startsWith($route, ['admin.category.', 'admin.category-translation.']) && $menu === 'life';
        $LTR = Str::startsWith($route, ['admin.topic.', 'admin.topic-translation.']) && $menu === 'life';
        $LCR = Str::startsWith($route, ['admin.content.', 'admin.content-translation.']) && $menu === 'life';

        $topicType = isset($type) ? $type : null;
    @endphp

    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>

    <nav class="pc-sidebar">
        <div class="navbar-wrapper">
            <div class="m-header d-flex justify-content-center">
                <a href="{{ route('admin.dashboard') }}" class="b-brand text-primary">
                    <img src="{{ asset('img/logo.png') }}" alt="{{ config('app.name') }}" class="logo logo-lg w-100"
                        style="height: 75px" />
                </a>
            </div>

            <div class="navbar-content">
                <ul class="pc-navbar">
                    <li class="pc-item">
                        <a href="{{ route('admin.dashboard') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-dashboard"></i></span>
                            <span class="pc-mtext">Dashboard</span>
                        </a>
                    </li>

                    @hasanyrole('Developer|Owner|Admin|Quran Admin|Quran Staff')
                        <li
                            class="pc-item pc-hasmenu {{ Str::is('admin.quran-chapters.*', $route) ||
                            Str::is('admin.quran-chapter-translations.*', $route) ||
                            Str::is('admin.quran-verses.*', $route)
                                ? 'active pc-trigger'
                                : '' }}">
                            <a href="javascript:void(0)" class="pc-link">
                                <span class="pc-micon"><i class="ti ti-book"></i></span>
                                <span class="pc-mtext">Quran</span>
                                <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
                            </a>
                            <ul class="pc-submenu">
                                <li
                                    class="pc-item {{ Str::is('admin.quran-chapters.*', $route) || Str::is('admin.quran-chapter-translations.*', $route)
                                        ? 'active'
                                        : '' }}">
                                    <a class="pc-link" href="{{ route('admin.quran-chapters.index') }}">Chapters</a>
                                </li>

                                <li class="pc-item {{ Str::is('admin.quran-verses.*', $route) ? 'active' : '' }}">
                                    <a class="pc-link" href="{{ route('admin.quran-verses.index') }}">Verses</a>
                                </li>
                            </ul>
                        </li>
                    @endhasanyrole

                    @hasanyrole('Developer|Owner|Admin|Hadith Admin|Hadith Staff')
                        <li
                            class="pc-item pc-hasmenu {{ Str::is('admin.hadith-books.*', $route) ||
                            Str::is('admin.hadith-book-translations.*', $route) ||
                            Str::is('admin.hadith-chapters.*', $route) ||
                            Str::is('admin.hadith-chapter-translations.*', $route) ||
                            Str::is('admin.hadith-verses.*', $route) ||
                            Str::is('admin.hadith-verse-translations.*', $route)
                                ? 'active pc-trigger'
                                : '' }}">
                            <a href="javascript:void(0)" class="pc-link">
                                <span class="pc-micon"><i class="ti ti-notes"></i></span>
                                <span class="pc-mtext">Hadith</span>
                                <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
                            </a>
                            <ul class="pc-submenu">
                                <li
                                    class="pc-item {{ Str::is('admin.hadith-books.*', $route) || Str::is('admin.hadith-book-translations.*', $route)
                                        ? 'active'
                                        : '' }}">
                                    <a class="pc-link" href="{{ route('admin.hadith-books.index') }}">Books</a>
                                </li>

                                <li
                                    class="pc-item {{ Str::is('admin.hadith-chapters.*', $route) || Str::is('admin.hadith-chapter-translations.*', $route)
                                        ? 'active'
                                        : '' }}">
                                    <a class="pc-link" href="{{ route('admin.hadith-chapters.index') }}">Chapters</a>
                                </li>

                                <li
                                    class="pc-item {{ Str::is('admin.hadith-verses.*', $route) || Str::is('admin.hadith-verse-translations.*', $route)
                                        ? 'active'
                                        : '' }}">
                                    <a class="pc-link" href="{{ route('admin.hadith-verses.index') }}">Verses</a>
                                </li>
                            </ul>
                        </li>
                    @endhasanyrole

                    @hasanyrole('Developer|Owner|Admin|Topic Admin|Topic Staff')
                        <li
                            class="pc-item pc-hasmenu {{ Str::is('admin.topics.*', $route) ||
                            Str::is('admin.topic-translations.*', $route) ||
                            Str::is('admin.topic-quran.*', $route) ||
                            Str::is('admin.topic-hadith.*', $route) ||
                            Str::is('admin.topic-video.*', $route)
                                ? 'active pc-trigger'
                                : '' }}">
                            <a href="javascript:void(0)" class="pc-link">
                                <span class="pc-micon"><i class="ti ti-color-swatch"></i></span>
                                <span class="pc-mtext">Islamic Knowledge</span>
                                <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
                            </a>

                            <ul class="pc-submenu">
                                <li class="pc-item {{ $topicType == 'menu' ? 'active' : '' }}">
                                    <a class="pc-link" href="{{ route('admin.topics.index', 'menu') }}">Menus</a>
                                </li>

                                <li class="pc-item {{ $topicType == 'module' ? 'active' : '' }}">
                                    <a class="pc-link" href="{{ route('admin.topics.index', 'module') }}">Modules</a>
                                </li>

                                <li class="pc-item {{ $topicType == 'question' ? 'active' : '' }}">
                                    <a class="pc-link" href="{{ route('admin.topics.index', 'question') }}">Questions</a>
                                </li>

                                <li class="pc-item {{ $topicType == 'answer' ? 'active' : '' }}">
                                    <a class="pc-link" href="{{ route('admin.topics.index', 'answer') }}">Answers</a>
                                </li>
                            </ul>
                        </li>
                    @endhasanyrole

                    @can('view users')
                        <li class="pc-item {{ Route::currentRouteName() == 'admin.users.index' ? 'active' : '' }}"
                            class="pc-link">
                            <a href="{{ route('admin.users.index') }}" class="pc-link">
                                <span class="pc-micon"><i class="ti ti-users"></i></span>
                                <span class="pc-mtext">Users</span>
                            </a>
                        </li>
                    @endcan

                    @if (auth()->user()->hasRole('Developer') ||
                            auth()->user()->hasAnyPermission('view staffs', 'view roles', 'view activity-logs'))
                        <li
                            class="pc-item pc-hasmenu
                        {{ Str::is('permissions.*', Route::currentRouteName()) ||
                        Str::is('roles.*', Route::currentRouteName()) ||
                        Str::is('staffs.*', Route::currentRouteName())
                            ? 'active pc-trigger'
                            : '' }}">
                            <a href="javascript:void(0)" class="pc-link">
                                <span class="pc-micon"><i class="ti ti-user-check"></i></span>
                                <span class="pc-mtext">Staffs and Roles</span>
                                <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
                            </a>
                            <ul class="pc-submenu">
                                @can('view staffs')
                                    <li
                                        class="pc-item {{ Route::currentRouteName() == 'admin.staffs.index' ? 'active' : '' }}">
                                        <a class="pc-link" href="{{ route('admin.staffs.index') }}">Staffs</a>
                                    </li>
                                @endcan

                                @can('view roles')
                                    <li
                                        class="pc-item {{ Route::currentRouteName() == 'admin.roles.index' ? 'active' : '' }}">
                                        <a class="pc-link" href="{{ route('admin.roles.index') }}">Roles</a>
                                    </li>
                                @endcan

                                @role('Developer')
                                    <li
                                        class="pc-item {{ Route::currentRouteName() == 'admin.permissions.index' ? 'active' : '' }}">
                                        <a class="pc-link" href="{{ route('admin.permissions.index') }}">Permissions</a>
                                    </li>
                                @endrole
                            </ul>
                        </li>

                        <li class="pc-item {{ Str::is('admin.activity-log', $route) ? 'active' : '' }}">
                            <a href="{{ route('admin.activity-log') }}" class="pc-link">
                                <span class="pc-micon"><i class="ti ti-activity"></i></span>
                                <span class="pc-mtext">Activity Logs</span>
                            </a>
                        </li>
                    @endif

                    @can('view settings')
                        <li class="pc-item {{ Route::currentRouteName() == 'admin.settings.index' ? 'active' : '' }}"
                            class="pc-link">
                            <a href="{{ route('admin.settings.index') }}" class="pc-link">
                                <span class="pc-micon"><i class="ti ti-settings"></i></span>
                                <span class="pc-mtext">General Settings</span>
                            </a>
                        </li>
                    @endcan
                </ul>
            </div>
        </div>
    </nav>

    <header class="pc-header">
        <div class="header-wrapper">
            <div class="me-auto pc-mob-drp">
                <ul class="list-unstyled">
                    <li class="pc-h-item header-mobile-collapse">
                        <a href="#" class="pc-head-link head-link-secondary ms-0" id="sidebar-hide">
                            <i class="ti ti-menu-2"></i>
                        </a>
                    </li>
                    <li class="pc-h-item pc-sidebar-popup">
                        <a href="#" class="pc-head-link head-link-secondary ms-0" id="mobile-collapse">
                            <i class="ti ti-menu-2"></i>
                        </a>
                    </li>
                    <li class="dropdown pc-h-item d-inline-flex d-md-none">
                        <a class="pc-head-link head-link-secondary dropdown-toggle arrow-none m-0"
                            data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false"
                            aria-expanded="false">
                            <i class="ti ti-search"></i>
                        </a>
                        <div class="dropdown-menu pc-h-dropdown drp-search">
                            <form class="px-3">
                                <div class="mb-0 d-flex align-items-center">
                                    <i data-feather="search"></i>
                                    <input type="search" class="form-control border-0 shadow-none"
                                        placeholder="Search here. . ." />
                                </div>
                            </form>
                        </div>
                    </li>
                    <li class="pc-h-item d-none d-md-inline-flex">
                        <form class="header-search">
                            <i data-feather="search" class="icon-search"></i>
                            <input type="search" class="form-control" placeholder="Search here. . ." />
                            <button class="btn btn-light-secondary btn-search">
                                <i class="ti ti-adjustments-horizontal"></i>
                            </button>
                        </form>
                    </li>
                </ul>
            </div>

            <div class="ms-auto">
                <ul class="list-unstyled">
                    <li class="dropdown pc-h-item header-user-profile">
                        <a class="pc-head-link head-link-primary dropdown-toggle arrow-none me-0"
                            data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false"
                            aria-expanded="false">
                            <img src="{{ asset('img/user.png') }}" alt="user-image" class="user-avtar" />
                            <span><i class="ti ti-settings"></i></span>
                        </a>

                        <div class="dropdown-menu dropdown-user-profile dropdown-menu-end pc-h-dropdown">
                            <div class="dropdown-header">
                                <h4>Hi, <span class="small text-muted">{{ Auth::user()->name }}</span></h4>
                                <p class="text-muted text-capitalize">{{ Auth::user()->getRoleNames()->first() }}</p>
                                <hr />

                                <div class="profile-notification-scroll position-relative"
                                    style="max-height: calc(100vh - 280px)">
                                    <a href="{{ route('admin.profile.edit') }}" class="dropdown-item">
                                        <i class="ti ti-settings"></i>
                                        <span>Account Settings</span>
                                    </a>

                                    <form method="POST" action="{{ route('admin.logout') }}">
                                        @csrf

                                        <a href="javascript:void(0);" class="dropdown-item"
                                            onclick="event.preventDefault(); this.closest('form').submit();">
                                            <i class="ti ti-logout"></i>
                                            <span>Logout</span>
                                        </a>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </header>

    <div class="pc-container">
        <div class="pc-content">
            @yield('content')
        </div>
    </div>

    <div id="backdrop-loader" class="backdrop-loader">
        <div class="spinner"></div>
    </div>

    <!-- Required Js -->
    <script src="{{ asset('admin/js/plugins/popper.min.js') }}"></script>
    <script src="{{ asset('admin/js/plugins/simplebar.min.js') }}"></script>
    <script src="{{ asset('admin/js/plugins/bootstrap.min.js') }}"></script>
    {{-- <script src="{{ asset('admin/js/fonts/custom-font.js') }}"></script> --}}
    <script src="{{ asset('admin/js/script.js') }}"></script>
    <script src="{{ asset('admin/js/theme.js') }}"></script>
    <script src="{{ asset('admin/js/plugins/feather.min.js') }}"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

    <script src="{{ asset('admin/js/custom.js') }}"></script>

    <script>
        window.logoPaths = {
            dark: "{{ asset('img/logo.png') }}",
            light: "{{ asset('img/logo.png') }}"
        };

        preset_change('preset-1');
    </script>

    @stack('scripts')

    <script>
        $(document).ready(function() {
            const dataTableWrapper = $('#dataTable_wrapper .dataTables_filter');
            $('.selectFilter').insertBefore(dataTableWrapper).css('display', 'inline-block');

            if ($('#createBtn').length) {
                $('#createBtn').insertAfter(dataTableWrapper).css('display', 'inline-block');
            }
            if ($('#reorderBtn').length) {
                $('#reorderBtn').insertAfter(dataTableWrapper).css('display', 'inline-block');
            }

            $('#dataTable_wrapper .dataTables_filter').parent().css({
                display: 'flex',
                justifyContent: 'flex-end',
                gap: '5px',
                alignItems: 'center'
            });
        });

        function toggleActive(url) {
            const token = "{{ csrf_token() }}";

            updateStatus(url, token);
        }
    </script>
</body>

</html>
