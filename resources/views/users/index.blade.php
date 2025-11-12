@extends('layouts.app')

@section('content')
    <div class="d-flex flex-column flex-lg-row justify-content-between gap-3 align-items-lg-center mb-4">
        <div>
            <h3 class="fw-semibold text-secondary mb-1">Manajemen Pengguna</h3>
            <p class="text-muted mb-0">Kelola akses pengguna sesuai peran.</p>
        </div>
        <a href="{{ route('users.create') }}" class="btn btn-primary">
            <i class="fas fa-user-plus me-2"></i>Tambah Pengguna
        </a>
    </div>

    <form method="GET" class="card border-0 mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6 col-lg-4">
                    <label for="search" class="form-label small text-uppercase text-muted">Pencarian</label>
                    <input type="text" name="search" id="search" class="form-control" placeholder="Nama atau username" value="{{ $filters['search'] ?? '' }}">
                </div>
                <div class="col-md-4 col-lg-3">
                    <label for="role" class="form-label small text-uppercase text-muted">Role</label>
                    <select name="role" id="role" class="form-select">
                        <option value="">Semua</option>
                        @foreach ($roles as $key => $label)
                            <option value="{{ $key }}" {{ ($filters['role'] ?? '') === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 col-lg-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-outline-primary w-100"><i class="fas fa-search me-2"></i>Filter</button>
                </div>
            </div>
        </div>
    </form>

    <div class="card border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $index => $user)
                        <tr>
                            <td>{{ $users->firstItem() + $index }}</td>
                            <td class="fw-semibold">{{ $user->name }}</td>
                            <td>{{ $user->username }}</td>
                            <td>{{ $user->email ?? '-' }}</td>
                            <td><span class="badge bg-primary">{{ strtoupper($user->role) }}</span></td>
                            <td class="text-end">
                                <div class="btn-group">
                                    <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-outline-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('Hapus pengguna ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Belum ada data pengguna.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white border-0">
            {{ $users->links() }}
        </div>
    </div>
@endsection

