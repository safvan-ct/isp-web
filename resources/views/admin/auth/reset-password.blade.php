@extends('layouts.admin-auth')

@section('content')
    <form method="POST" action="{{ route('password.store') }}" class="mt-3">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <x-admin.input type="email" name="email" label="Email" placeholder="Email" required autofocus
            autocomplete="email" />

        <x-admin.input type="password" name="password" label="Password" placeholder="Password" required
            autocomplete="new-password" />

        <x-admin.input type="password" name="password_confirmation" label="Confirm Password" placeholder="Confirm Password"
            required autocomplete="new-password" />

        <div class="d-grid mt-4">
            <x-admin.button>Reset Password</x-admin.button>
        </div>
    </form>
@endsection
