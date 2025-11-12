@extends('layouts.app')

@section('content')
    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card border-0">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0 text-secondary">Informasi Profil</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PATCH')
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Lengkap</label>
                            <input id="name" name="name" type="text" class="form-control" value="{{ old('name', $user->name) }}" required autofocus>
                        </div>
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input id="username" name="username" type="text" class="form-control" value="{{ old('username', $user->username) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email (opsional)</label>
                            <input id="email" name="email" type="email" class="form-control" value="{{ old('email', $user->email) }}">
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Simpan Perubahan
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card border-0">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0 text-secondary">Ubah Password</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Password Saat Ini</label>
                            <input id="current_password" name="current_password" type="password" class="form-control" required autocomplete="current-password">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password Baru</label>
                            <input id="password" name="password" type="password" class="form-control" required autocomplete="new-password" minlength="8">
                        </div>
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                            <input id="password_confirmation" name="password_confirmation" type="password" class="form-control" required autocomplete="new-password" minlength="8">
                        </div>
                        <button type="submit" class="btn btn-outline-primary">
                            <i class="fas fa-key me-2"></i>Perbarui Password
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card border-0">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0 text-secondary text-danger"><i class="fas fa-exclamation-triangle me-2"></i>Hapus Akun</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">Menghapus akun akan menghilangkan semua data profil Anda. Tindakan ini tidak dapat dibatalkan.</p>
                    <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                        <i class="fas fa-user-slash me-2"></i>Hapus Akun
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteAccountLabel">Konfirmasi Penghapusan Akun</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Masukkan password untuk mengkonfirmasi penghapusan akun ini.</p>
                    <form id="delete-account-form" method="POST" action="{{ route('profile.destroy') }}">
                        @csrf
                        @method('DELETE')
                        <div class="mb-3">
                            <label for="delete_password" class="form-label">Password</label>
                            <input id="delete_password" name="password" type="password" class="form-control" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" form="delete-account-form" class="btn btn-danger">Hapus Akun</button>
                </div>
            </div>
        </div>
    </div>
@endsection
