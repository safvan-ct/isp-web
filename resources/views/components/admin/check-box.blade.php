@props(['name', 'label'])

<div class="form-check">
    <input class="form-check-input input-primary" type="checkbox" id="{{ $name }}" name="{{ $name }}" />
    <label class="form-check-label text-muted" for="{{ $name }}">{{ $label }}</label>
</div>
