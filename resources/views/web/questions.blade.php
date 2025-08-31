@extends('layouts.web')

@section('title', $module->translation?->title ?? $module->slug)

@section('content')
    <x-web.page-header :title="$module->translation?->title ?? $module->slug" :subtitle="$module->translation?->sub_title" :breadcrumbs="[
        ['label' => __('app.home'), 'url' => route('home')],
        [
            'label' => $module->parent->translation?->title ?? $module->parent->slug,
            'url' => route('modules.show', $module->parent->slug),
        ],
        ['label' => $module->translation?->title ?? $module->slug],
    ]" />

    <x-web.container class="col-two">
        @foreach ($module->children as $item)
            <a class="section-card mb-2 d-flex justify-content-between  align-items-center"
                href="{{ route('answers.show', ['menu_slug' => $module->parent->slug, 'module_slug' => $module->slug, 'question_slug' => $item->slug]) }}">
                <span>
                    <span class="badge bg-primary">{{ $loop->iteration }}</span> -
                    {{ $item->translation?->title ?? $item->slug }}
                </span>
                <span>
                    <i class="bi bi-arrow-right-circle-fill fs-4"></i>
                </span>
            </a>
        @endforeach
    </x-web.container>
@endsection

@push('scripts')
@endpush
