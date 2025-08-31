@extends('layouts.admin-auth')

@section('content')
    <x-admin.auth-header header="Sign up" subheader="Enter your credentials to continue" />

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <x-admin.input name="name" label="Name" placeholder="Name" required autofocus />

        <x-admin.input type="email" name="email" label="Email Address / Username" placeholder="Email address / Username"
            required autocomplete="email" />

        <x-admin.input type="password" name="password" label="Password" placeholder="Password" required
            autocomplete="new-password" />

        <x-admin.input type="password" name="password_confirmation" label="Confirm Password" placeholder="Confirm Password"
            required autocomplete="new-password" />

        <div class="d-grid mt-4">
            <x-admin.button>Sign Up</x-admin.button>
        </div>
    </form>

    <hr />
    <h5 class="d-flex justify-content-center">
        <x-admin.link :url="route('login')">Already have an account?</x-admin.link>
    </h5>
@endsection
