@extends('layouts.web')

@section('title', __('app.quran') . ' | ' . ($chapter->translation?->name ?? $chapter->name))

@section('content')
    <x-web.container>
        <x-web.index-card class="b-top">
            <x-web.chapter-header :name="(optional($chapter->translation)->name ? $chapter->translation->name . ' | ' : '') .
                $chapter->name">

                <div>
                    {{ __('app.chapter') }}: <strong class="ar-number">{{ $chapter->id }}</strong> |
                    {{ __('app.ayahs') }}: <strong class="ar-number">{{ $chapter->no_of_verses }}</strong>
                </div>
            </x-web.chapter-header>

            @include('web.partials.ayah-list', ['result' => $chapter->verses])
        </x-web.index-card>
    </x-web.container>
@endsection

@push('scripts')
    <script>
        $(function() {
            updateAllLikeIcon('quran');
            updateAllBookmarkIcon('quran');
        });
    </script>
@endpush
