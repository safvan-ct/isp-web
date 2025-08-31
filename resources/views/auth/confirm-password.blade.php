@extends('layouts.admin-auth')

@section('content')
    <x-admin.auth-header
        subheader="This is a secure area of the application. Please confirm your password before continuing." />

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <x-admin.input type="password" name="password" label="Password" placeholder="Password" required
            autocomplete="current-password" autofocus />

        <div class="d-grid">
            <x-admin.button>Confirm</x-admin.button>
        </div>
    </form>
@endsection
