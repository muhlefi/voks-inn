@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-semibold text-secondary mb-1">Data Kamar</h3>
            <p class="text-muted mb-0">Kelola kamar hotel dan status ketersediaannya.</p>
        </div>
        <a href="{{ route('rooms.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Tambah Kamar
        </a>
    </div>

    <div class="card border-0">
        <div class="table-responsive">
            <table class="table table-striped align-middle mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Kode</th>
                        <th>Nama Kamar</th>
                        <th>Tipe</th>
                        <th>Harga / Malam</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($rooms as $index => $room)
                        <tr>
                            <td>{{ $rooms->firstItem() + $index }}</td>
                            <td class="fw-semibold">{{ $room->kode_kamar }}</td>
                            <td>{{ $room->nama_kamar }}</td>
                            <td>{{ $room->roomType->nama_tipe }}</td>
                            <td>Rp {{ number_format($room->harga_per_malam, 0, ',', '.') }}</td>
                            <td>
                                <span class="status-room {{ $room->status }}">
                                    <i class="fas fa-circle"></i> {{ ucfirst($room->status) }}
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="btn-group">
                                    <a href="{{ route('rooms.edit', $room) }}" class="btn btn-sm btn-outline-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('rooms.destroy', $room) }}" method="POST" onsubmit="return confirm('Hapus kamar ini?')">
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
                            <td colspan="7" class="text-center text-muted py-4">Belum ada data kamar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white border-0">
            {{ $rooms->links() }}
        </div>
    </div>
@endsection

