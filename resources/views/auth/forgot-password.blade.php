@extends('layouts.guest')

@section('content')
    <div class="alert alert-info mb-4" role="alert">
        Kami akan mengirimkan tautan reset password ke email Anda.
    </div>

    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
            @error('email')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary w-100">
            <i class="fas fa-paper-plane me-2"></i>Kirim Tautan Reset
        </button>
    </form>
@endsection
