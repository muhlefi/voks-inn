@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-semibold text-secondary mb-1">Tambah Transaksi</h3>
            <p class="text-muted mb-0">Catat pemasukan atau pengeluaran manual.</p>
        </div>
        <a href="{{ route('transactions.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <div class="card border-0">
        <div class="card-body">
            <form action="{{ route('transactions.store') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="tanggal" class="form-label">Tanggal</label>
                        <input type="date" name="tanggal" id="tanggal" class="form-control" value="{{ old('tanggal', now()->toDateString()) }}" required>
                    </div>
                    <div class="col-md-4">
                        <label for="tipe" class="form-label">Tipe Transaksi</label>
                        <select name="tipe" id="tipe" class="form-select" required>
                            <option value="pemasukan" {{ old('tipe') === 'pemasukan' ? 'selected' : '' }}>Pemasukan</option>
                            <option value="pengeluaran" {{ old('tipe') === 'pengeluaran' ? 'selected' : '' }}>Pengeluaran</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="nominal" class="form-label">Nominal</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="nominal" id="nominal" class="form-control" min="0" step="1000" value="{{ old('nominal') }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <input type="text" name="keterangan" id="keterangan" class="form-control" value="{{ old('keterangan') }}" required placeholder="Contoh: Pembelian perlengkapan">
                    </div>
                    <div class="col-md-6">
                        <label for="reservation_id" class="form-label">Tautkan dengan Reservasi (opsional)</label>
                        <select name="reservation_id" id="reservation_id" class="form-select">
                            <option value="">-- Tanpa Reservasi --</option>
                            @foreach ($reservations as $res)
                                <option value="{{ $res->id }}" {{ old('reservation_id') == $res->id ? 'selected' : '' }}>
                                    RES-{{ str_pad($res->id, 5, '0', STR_PAD_LEFT) }} • {{ $res->nama_tamu }} • {{ $res->room->nama_kamar ?? '-' }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Hanya gunakan jika transaksi terkait dengan reservasi tertentu.</small>
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Simpan Transaksi
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

