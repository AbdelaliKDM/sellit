@extends('layouts.blank')

@section('content')
<div class="card w-100">
    <div class="card-header bg-dark text-white">
        <h4 class="mb-0">Login</h4>
    </div>
    <div class="card-body p-4">
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="mb-3 form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                <label class="form-check-label" for="remember">
                    Remember Me
                </label>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">
                    Login
                </button>
            </div>

            @if($registrationEnabled)
            <div class="mt-3 text-center">
                <a href="{{ route('register') }}" class="btn btn-outline-secondary">
                    Create New Account
                </a>
            </div>
            @endif
        </form>
    </div>
</div>
@endsection
