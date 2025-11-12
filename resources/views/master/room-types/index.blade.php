@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-semibold text-secondary mb-1">Data Tipe Kamar</h3>
            <p class="text-muted mb-0">Kelola tipe kamar dan fasilitas yang tersedia.</p>
        </div>
        <a href="{{ route('room-types.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Tambah Tipe
        </a>
    </div>

    <div class="card border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Tipe</th>
                        <th>Fasilitas</th>
                        <th>Jumlah Kamar</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($roomTypes as $index => $type)
                        <tr>
                            <td>{{ $roomTypes->firstItem() + $index }}</td>
                            <td class="fw-semibold">{{ $type->nama_tipe }}</td>
                            <td>{{ $type->fasilitas }}</td>
                            <td>
                                <span class="badge bg-info">{{ $type->rooms_count }}</span>
                            </td>
                            <td class="text-end">
                                <div class="btn-group">
                                    <a href="{{ route('room-types.edit', $type) }}" class="btn btn-sm btn-outline-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('room-types.destroy', $type) }}" method="POST" onsubmit="return confirm('Hapus tipe kamar ini?')">
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
                            <td colspan="5" class="text-center text-muted py-4">Belum ada data tipe kamar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white border-0">
            {{ $roomTypes->links() }}
        </div>
    </div>
@endsection

