@props(['title' => null, 'description' => null])

<header class="page-hero">
    <div class="text-center">
        {!! $title ? "<h5 class='text-primary fw-bold text-Playfair'>{$title}</h5>" : '' !!}
        {!! $description ? "<p class='text-muted m-0'>{$description}</p>" : '' !!}
    </div>

    {{ $slot }}
</header>
