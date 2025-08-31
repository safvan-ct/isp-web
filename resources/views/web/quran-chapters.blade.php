@extends('layouts.web')

@section('title', __('app.quran'))

@section('content')
    <x-web.container>
        <x-web.index-card :title="'القرآن الكريم'" :description="__('app.quran_desc')" class="b-top">
            <x-web.index-list>
                @foreach ($chapters as $chapter)
                    <x-web.index-list-item :href="route('quran.chapter', $chapter->id)">
                        {{ $chapter->id }}. {{ $chapter->translation?->name ?? $chapter->name }}
                    </x-web.index-list-item>
                @endforeach
            </x-web.index-list>
        </x-web.index-card>
    </x-web.container>
@endsection
