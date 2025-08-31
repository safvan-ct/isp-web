@props(['title' => '', 'subtitle' => '', 'breadcrumbs' => []])

<header class="text-white text-center py-3 notranslate">
    <h3 class="text-white">{!! $title !!}</h3>

    @if (!empty($subtitle))
        <p class="text-white">{!! $subtitle !!}</p>
    @endif

    @if (!empty($breadcrumbs))
        <nav aria-label="breadcrumb" class="custom-breadcrumb rounded p-2 mt-1">
            <ol class="breadcrumb justify-content-center mb-0">
                @foreach ($breadcrumbs as $breadcrumb)
                    @if (!$loop->last)
                        <li class="breadcrumb-item">
                            <a href="{{ $breadcrumb['url'] }}" class="text-decoration-none">
                                {{ $breadcrumb['label'] }}
                            </a>
                        </li>
                    @else
                        <li class="breadcrumb-item active text-muted" aria-current="page">
                            {{ $breadcrumb['label'] }}
                        </li>
                    @endif
                @endforeach
            </ol>
        </nav>
    @endif
</header>
