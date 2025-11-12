@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-semibold text-secondary mb-1">Form Check-In</h3>
            <p class="text-muted mb-0">Catat data tamu dan kamar yang dipilih.</p>
        </div>
        <a href="{{ route('reservations.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <div class="card border-0">
        <div class="card-body">
            <form action="{{ route('reservations.store') }}" method="POST">
                @csrf
                <div class="row g-4">
                    <div class="col-lg-6">
                        <h5 class="fw-semibold text-secondary mb-3">Informasi Tamu</h5>
                        <div class="mb-3">
                            <label for="nama_tamu" class="form-label">Nama Tamu</label>
                            <input type="text" name="nama_tamu" id="nama_tamu" class="form-control" value="{{ old('nama_tamu') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="no_identitas" class="form-label">No. Identitas</label>
                            <input type="text" name="no_identitas" id="no_identitas" class="form-control" value="{{ old('no_identitas') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="jumlah_tamu" class="form-label">Jumlah Tamu</label>
                            <input type="number" name="jumlah_tamu" id="jumlah_tamu" class="form-control" min="1" value="{{ old('jumlah_tamu', 1) }}" required>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <h5 class="fw-semibold text-secondary mb-3">Detil Menginap</h5>
                        <div class="mb-3">
                            <label for="room_id" class="form-label">Pilih Kamar</label>
                            <select name="room_id" id="room_id" class="form-select" required>
                                <option value="">-- Pilih Kamar --</option>
                                @foreach ($rooms as $room)
                                    <option value="{{ $room->id }}" data-price="{{ $room->harga_per_malam }}" {{ old('room_id') == $room->id ? 'selected' : '' }}>
                                        {{ $room->kode_kamar }} - {{ $room->nama_kamar }} ({{ $room->roomType->nama_tipe }}) - Rp {{ number_format($room->harga_per_malam, 0, ',', '.') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="check_in" class="form-label">Check-In</label>
                                <input type="date" name="check_in" id="check_in" class="form-control" value="{{ old('check_in', now()->toDateString()) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="check_out" class="form-label">Check-Out</label>
                                <input type="date" name="check_out" id="check_out" class="form-control" value="{{ old('check_out', now()->addDay()->toDateString()) }}" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-4 d-flex justify-content-end gap-2">
                    <button type="reset" class="btn btn-outline-secondary">Reset</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Simpan Check-In
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

