@props(['link' => 'javascript:void(0)', 'text' => 'Create', 'type' => 'default', 'class' => 'btn-sm'])

@php
    $classes = [
        'success' => 'btn-success',
        'error' => 'btn-danger',
        'warning' => 'btn-warning',
        'info' => 'btn-info',
        'default' => 'btn-primary',
        'link' => 'btn-link',
    ];
@endphp

<a href="{{ $link }}" class="btn {{ $classes[$type] }} {{ $class }}" {{ $attributes }}>
    {{ $text }}
</a>
