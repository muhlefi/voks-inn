@extends('layouts.guest')

@section('content')
    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="mb-3">
            <label for="username" class="form-label text-uppercase small fw-semibold">Username</label>
            <div class="input-group">
                <span class="input-group-text bg-white"><i class="fas fa-user text-muted"></i></span>
                <input type="text" id="username" name="username" class="form-control" value="{{ old('username') }}" required autofocus autocomplete="username" placeholder="Masukkan username">
            </div>
            @error('username')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label text-uppercase small fw-semibold">Password</label>
            <div class="input-group">
                <span class="input-group-text bg-white"><i class="fas fa-lock text-muted"></i></span>
                <input type="password" id="password" name="password" class="form-control" required autocomplete="current-password" placeholder="••••••••">
            </div>
            @error('password')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex align-items-center justify-content-between mb-4">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                <label class="form-check-label" for="remember">
                    Ingat saya
                </label>
            </div>
            @if (Route::has('password.request'))
                <a class="small text-decoration-none" href="{{ route('password.request') }}">
                    Lupa password?
                </a>
            @endif
        </div>

        <button type="submit" class="btn btn-primary w-100 py-2">
            <i class="fas fa-sign-in-alt me-2"></i> Masuk
        </button>
    </form>
@endsection
