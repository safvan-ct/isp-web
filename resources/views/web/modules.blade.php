@extends('layouts.web')

@section('title', $topic->translation?->title ?: $topic->slug)

@section('content')
    <x-web.page-header :title="$topic->translation?->title ?: $topic->slug" />

    <x-web.container>
        <div class="row g-4">
            @foreach ($topic->children as $item)
                <div class="col-md-4">
                    <div class="index-card d-flex flex-column justify-content-between h-100">
                        <span>
                            <h5 class="fw-bold">{{ $item->translation?->title ?: $item->slug }}</h5>
                            {!! $item->translation?->content !!}
                        </span>

                        <a href="{{ route('questions.show', ['menu_slug' => $topic->slug, 'module_slug' => $item->slug]) }}"
                            class="btn btn-primary mt-3 mb-0">
                            {{ __('app.know_more') }}
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </x-web.container>
@endsection

@push('scripts')
@endpush
