@extends('layouts.web')

@section('content')
    <x-web.page-header :title="__('app.islamic_study_portal')" :subtitle="__('app.home_sub_title')" />

    <x-web.container class="notranslate">
        <div class="row g-4">
            <div class="col-md-4">
                <x-web.home-card :icon="'<i class=\'fas fa-quran\'></i>'" :title="__('app.quran')" :description="__('app.quran_sub_title')"
                    :href="route('quran.index')" :btnText="__('app.start_learning')" />
            </div>

            <div class="col-md-4">
                <x-web.home-card :icon="'<i class=\'fas fa-book\'></i>'" :title="__('app.hadith')" :description="__('app.hadith_sub_title')"
                    :href="route('hadith.index')" :btnText="__('app.view')" />
            </div>

            <div class="col-md-4">
                <x-web.home-card :icon="'<i class=\'fas fa-calendar-alt\'></i>'" :title="__('app.islamic_calendar')"
                    :description="__('app.islamic_calendar_sub_title')" :href="route('calendar')"
                    :btnText="__('app.view_days')" />
            </div>
        </div>

        @if ($modules->isNotEmpty())
            <div class="text-center mt-5 mb-3">
                <h4 class="fw-bold">{{ __('app.important_topics') }}</h4>
                <div class="section-divider mx-auto"></div>
            </div>
        @endif

        <div class="row g-4 justify-content-center">
            @foreach ($modules as $item)
                <div class="col-md-4">
                    <x-web.home-card :href="route('questions.show', [
                        'menu_slug' => $item->parent->slug,
                        'module_slug' => $item->slug,
                    ])" :title="$item->translation?->title ?? $item->slug" :description="$item->translation?->sub_title" :btnText="__('app.know_more')" />
                </div>
            @endforeach
        </div>
    </x-web.container>
@endsection
