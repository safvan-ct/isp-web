@props(['title' => '', 'subTitle' => ''])

<div class="card">
    @if (!empty($title) || !empty($subTitle))
        <div class="card-header">
            @if (!empty($title))
                <h5 class="card-title">{{ $title }}</h5>
            @endif

            @if (!empty($subTitle))
                <p class="text-md text-gray-600">{{ $subTitle }}</p>
            @endif
        </div>
    @endif

    <div class="card-body">
        {{ $slot }}
    </div>
</div>
