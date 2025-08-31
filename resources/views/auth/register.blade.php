@extends('layouts.web-auth')

@section('content')
    <div id="loginWrapper" class="d-flex justify-content-center align-items-center py-3" style="height:100%; overflow-y:auto;">
        <div class="w-100" style="max-width:400px;">
            <div class="card p-4 shadow">
                <h3 class="text-center mb-4 text-primary">Sign up</h3>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('register') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label text-primary">Name</label>
                        <input type="text" class="form-control" id="name" placeholder="Enter name" name="name"
                            value="{{ old('name') }}" required />
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label text-primary">Email address</label>
                        <input type="email" class="form-control" id="email" placeholder="Enter email" name="email"
                            value="{{ old('email') }}" required />
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label text-primary">Password</label>
                        <input type="password" class="form-control" id="password" placeholder="Password" name="password"
                            required />
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label text-primary">Confirm Password</label>
                        <input type="password" class="form-control" id="password_confirmation"
                            placeholder="Confirm Password" name="password_confirmation" required />
                    </div>

                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-primary">Sign Up</button>
                    </div>

                    <div class="text-center">
                        <p class="mb-0 text-primary">
                            Already have an account?
                            <a href="{{ route('login') }}" class="text-decoration-none text-primary fw-bold">
                                Login
                            </a>
                        </p>
                    </div>

                    <div class="text-center">
                        <p class="mb-0 text-primary">Back to home
                            <a href="{{ route('home') }}"
                                class="text-decoration-none text-primary fw-bold">{{ __('app.home') }}</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
