@extends('layouts.guest')

@section('content')
    <div class="alert alert-info mb-4" role="alert">
        Terima kasih telah mendaftar! Silakan verifikasi email Anda melalui tautan yang telah kami kirim.
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Tautan verifikasi baru telah dikirim ke email Anda.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form method="POST" action="{{ route('verification.send') }}" class="d-grid gap-2">
        @csrf
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-paper-plane me-2"></i>Kirim Ulang Email Verifikasi
        </button>
    </form>

    <form method="POST" action="{{ route('logout') }}" class="mt-3">
        @csrf
        <button type="submit" class="btn btn-outline-secondary w-100">
            <i class="fas fa-sign-out-alt me-2"></i>Keluar
        </button>
    </form>
@endsection
