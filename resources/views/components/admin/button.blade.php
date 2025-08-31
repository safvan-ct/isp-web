@props(['type' => 'submit', 'class' => 'btn btn-secondary'])

<button type="{{ $type }}" class="{{ $class }}" {{ $attributes }}>
    {{ $slot }}
</button>
