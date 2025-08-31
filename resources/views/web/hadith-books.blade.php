@extends('layouts.web')

@section('title', __('app.hadith_books'))

@section('content')
    <x-web.container>
        <div class="row g-4">
            @foreach ($books as $item)
                <div class="col-lg-3 col-md-6 col-12">
                    <div class="index-card p-3 d-flex flex-column text-center justify-content-between h-100 b-top">
                        <span>
                            <div class="small text-uppercase opacity-75 mb-1">{{ $item->slug }}</div>
                            <h4 class="book-title fw-bold">{{ $item->translation?->name ?: $item->name }}</h4>
                            <div class="fst-italic small mt-1">
                                {{ $item->translation?->writer ?: $item->writer }}
                                <span class="opacity-75">({{ $item->writer_death_year }})</span>
                            </div>
                            <div class="small">
                                <span class="fw-bold">{{ $item->hadith_count }}</span> {{ __('app.hadiths') }} |
                                <span class="fw-bold">{{ $item->chapter_count }}</span> {{ __('app.chapters') }}
                            </div>
                        </span>

                        <div class="mt-2">
                            <a href="{{ route('hadith.chapters', [$item->id]) }}" class="btn btn-primary w-100 fw-bold">
                                {{ __('app.view_chapters') }}
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </x-web.container>
@endsection
