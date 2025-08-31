@props(['type' => 'success', 'message' => ''])

@php
    $classes = [
        'success' => 'alert-success',
        'error' => 'alert-danger',
        'warning' => 'alert-warning',
        'info' => 'alert-info',
    ];
@endphp

@session($type)
    <div class="alert {{ $classes[$type] }} alert-dismissible fade show" role="alert">
        {{ session($type) }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@elseif($message)
    <div class="alert {{ $classes[$type] }} alert-dismissible fade show" role="alert">
        {{ $message }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endsession
