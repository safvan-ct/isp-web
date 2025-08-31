@props(['title', 'breadcrumb' => []])

<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col">
                <div class="page-header-title">
                    <h5 class="m-b-10">{{ $title }}</h5>
                </div>
            </div>
            <div class="col-auto">
                <ul class="breadcrumb">
                    @foreach ($breadcrumb as $item)
                        <li class="breadcrumb-item">
                            @if (isset($item['link']))
                                <a href="{{ $item['link'] }}">{{ $item['label'] }}</a>
                            @else
                                {{ $item['label'] }}
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
