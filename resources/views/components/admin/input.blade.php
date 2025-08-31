@props(['name', 'label', 'value' => null, 'type' => 'text', 'error' => true])

@php
    $value = $type === 'password' ? '' : $value ?? old($name);
@endphp

<div class="form-floating mb-2 {{ $name }}Div">
    <input type="{{ $type }}" class="form-control" id="{{ $name }}" name="{{ $name }}"
        value="{{ $value }}" {{ $attributes }} />
    <label for="{{ $name }}">{{ $label }}</label>

    @if ($error && $errors->has($name))
        <x-admin.input-error :messages="$errors->get($name)" class="mt-2" />
    @endif
</div>
