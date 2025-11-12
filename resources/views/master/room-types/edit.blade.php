         @extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-semibold text-secondary mb-1">Ubah Tipe Kamar</h3>
            <p class="text-muted mb-0">Perbarui informasi tipe kamar.</p>
        </div>
        <a href="{{ route('room-types.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <div class="card border-0">
        <div class="card-body">
            <form action="{{ route('room-types.update', $roomType) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="nama_tipe" class="form-label">Nama Tipe</label>
                    <input type="text" name="nama_tipe" id="nama_tipe" class="form-control" value="{{ old('nama_tipe', $roomType->nama_tipe) }}" required>
                </div>
                <div class="mb-3">
                    <label for="fasilitas" class="form-label">Fasilitas</label>
                    <textarea name="fasilitas" id="fasilitas" rows="4" class="form-control" required>{{ old('fasilitas', $roomType->fasilitas) }}</textarea>
                </div>
                <div class="mb-3">
                    <label for="jumlah_kamar" class="form-label">Jumlah Kamar</label>
                    <input type="number" name="jumlah_kamar" id="jumlah_kamar" class="form-control" value="{{ old('jumlah_kamar', $roomType->jumlah_kamar) }}" required>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Perbarui
                </button>
            </form>
        </div>
    </div>
@endsection

