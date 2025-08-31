@extends('layouts.admin-auth')

@section('content')
    <x-admin.auth-header header="Hi, Welcome Back" subheader="Enter your credentials to continue" />

    @if ($errors->get('email'))
        <x-admin.alert type="error" :message="$errors->first('email')" />
    @endif

    <form method="POST" action="{{ route('admin.login') }}">
        @csrf

        <x-admin.input type="email" name="email" label="Email Address / Username" error="0"
            placeholder="Email address / Username" required autofocus autocomplete="username" />

        <x-admin.input type="password" name="password" label="Password" placeholder="Password" required
            autocomplete="current-password" />

        <div class="d-flex mt-1 justify-content-between">
            <x-admin.check-box name="remember" label="Remember me" />
        </div>

        <div class="d-grid mt-4">
            <x-admin.button>Sign In</x-admin.button>
        </div>
    </form>
@endsection
