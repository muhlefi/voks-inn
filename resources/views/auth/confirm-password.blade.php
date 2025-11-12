@extends('layouts.guest')

@section('content')
    <div class="alert alert-warning mb-4" role="alert">
        Area ini memerlukan konfirmasi password sebelum melanjutkan.
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" id="password" name="password" class="form-control" required autocomplete="current-password">
            @error('password')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary w-100">
            <i class="fas fa-shield-alt me-2"></i>Konfirmasi
        </button>
    </form>
@endsection
