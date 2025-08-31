@extends('layouts.web-auth')

@section('content')
    <div id="loginWrapper" class="d-flex justify-content-center align-items-center py-3" style="height:100%; overflow-y:auto;">
        <div class="w-100" style="max-width:400px;">
            <div class="card p-4 shadow">
                <h3 class="text-center mb-4 text-primary">Login</h3>

                @if ($errors->get('email'))
                    <x-admin.alert type="error" :message="$errors->first('email')" />
                @endif

                <form action="{{ route('login') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label text-primary">Email address</label>
                        <input type="email" class="form-control" id="email" placeholder="Enter email" name="email"
                            value="{{ old('email') }}" required autocomplete="off">
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label text-primary">Password</label>
                        <input type="password" class="form-control" id="password" placeholder="Password" name="password"
                            required autocomplete="off">
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="rememberMe" name="remember">
                            <label class="form-check-label text-primary" for="rememberMe">Remember Me</label>
                        </div>
                        <div>
                            <a href="#" class="text-decoration-none text-primary">Forgot Password?</a>
                        </div>
                    </div>

                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-primary">Login</button>
                    </div>

                    <div class="text-center mb-1">
                        <p class="mb-0 text-primary">Don't have an account?
                            <a href="{{ route('register') }}" class="text-decoration-none text-primary fw-bold">Register</a>
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
