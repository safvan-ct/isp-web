@extends('layouts.admin-auth')

@section('content')
    <x-admin.auth-header
        subheader="Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one." />

    <x-admin.auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <x-admin.input type="email" name="email" label="Email" placeholder="Email" autofocus required />

        <div class="d-grid mt-4">
            <x-admin.button>Email Password Reset Link</x-admin.button>
        </div>
    </form>

    <hr />
    <h5 class="d-flex justify-content-center">
        <x-admin.link :url="route('login')">Sign In</x-admin.link>
    </h5>
@endsection
