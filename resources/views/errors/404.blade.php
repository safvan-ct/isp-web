@extends('layouts.web')

@section('content')
    <main class="container my-3 flex-grow-1 d-flex justify-content-center align-items-center">
        <div class="index-card text-center">
            <h1 style="font-size:80px; font-weight:bold;">404</h1>
            <h3>Oops! The page you’re looking for doesn’t exist.</h3>
            <a href="{{ url('/') }}" class="nav-link text-decoration-underline">Go back home</a>
        </div>
    </main>
@endsection
