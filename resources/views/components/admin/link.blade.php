@props(['url' => '#', 'class' => 'text-secondary'])

<a href="{{ $url }}" class="{{ $class }}" {{ $attributes }}>
    {{ $slot }}
</a>
