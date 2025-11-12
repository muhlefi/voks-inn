@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-semibold text-secondary mb-1">Tambah Kamar</h3>
            <p class="text-muted mb-0">Lengkapi informasi kamar baru.</p>
        </div>
        <a href="{{ route('rooms.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <div class="card border-0">
        <div class="card-body">
            <form action="{{ route('rooms.store') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="kode_kamar" class="form-label">Kode Kamar</label>
                        <input type="text" name="kode_kamar" id="kode_kamar" class="form-control" value="{{ old('kode_kamar') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label for="nama_kamar" class="form-label">Nama Kamar</label>
                        <input type="text" name="nama_kamar" id="nama_kamar" class="form-control" value="{{ old('nama_kamar') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label for="room_type_id" class="form-label">Tipe Kamar</label>
                        <select name="room_type_id" id="room_type_id" class="form-select" required>
                            <option value="">-- Pilih Tipe --</option>
                            @foreach ($roomTypes as $type)
                                <option value="{{ $type->id }}" {{ old('room_type_id') == $type->id ? 'selected' : '' }}>
                                    {{ $type->nama_tipe }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="harga_per_malam" class="form-label">Harga per Malam</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="harga_per_malam" id="harga_per_malam" class="form-control" value="{{ old('harga_per_malam') }}" min="0" step="5000" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="kosong" {{ old('status') === 'kosong' ? 'selected' : '' }}>Kosong</option>
                            <option value="terisi" {{ old('status') === 'terisi' ? 'selected' : '' }}>Terisi</option>
                            <option value="maintenance" {{ old('status') === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                        </select>
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

