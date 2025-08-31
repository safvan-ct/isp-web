@extends('layouts.admin-auth')

@section('content')
    <x-admin.auth-header
        subheader="Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn't receive the email, we will gladly send you another." />

    @if (session('status') == 'verification-link-sent')
        <x-admin.alert type="success"
            message="A new verification link has been sent to the email address you provided during registration." />
    @endif

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <div class="d-grid">
            <x-admin.button>Resend Verification Email</x-admin.button>
        </div>
    </form>

    <hr />
    <h5 class="d-flex justify-content-center">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <x-admin.button>Log Out</x-admin.button>
        </form>
    </h5>
@endsection
