@extends('layouts.app')

@section('content')
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
        <div>
            <h3 class="fw-semibold text-secondary mb-1">Laporan Reservasi</h3>
            <p class="text-muted mb-0">Ringkasan reservasi berdasarkan periode dan status.</p>
        </div>
        <a href="{{ route('reports.reservations.pdf', request()->query()) }}" target="_blank" class="btn btn-outline-primary">
            <i class="fas fa-file-pdf me-2"></i>Export PDF
        </a>
    </div>

    <form method="GET" class="card border-0 mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label small text-uppercase text-muted">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua</option>
                        <option value="menginap" {{ ($filters['status'] ?? '') === 'menginap' ? 'selected' : '' }}>Menginap</option>
                        <option value="menunggu_pengecekan" {{ ($filters['status'] ?? '') === 'menunggu_pengecekan' ? 'selected' : '' }}>Menunggu Pengecekan</option>
                        <option value="selesai" {{ ($filters['status'] ?? '') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-uppercase text-muted">Dari</label>
                    <input type="date" name="from" class="form-control" value="{{ $filters['from'] ?? '' }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-uppercase text-muted">Sampai</label>
                    <input type="date" name="to" class="form-control" value="{{ $filters['to'] ?? '' }}">
                </div>
                <div class="col-md-3 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-outline-primary flex-grow-1">
                        <i class="fas fa-filter me-2"></i> Terapkan
                    </button>
                    <a href="{{ route('reports.reservations') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-undo"></i>
                    </a>
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
                        <th>Tanggal</th>
                        <th>Nama Tamu</th>
                        <th>Kamar</th>
                        <th>Status</th>
                        <th>Subtotal</th>
                        <th>Denda</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($reservations as $index => $reservation)
                        <tr>
                            <td>{{ $reservations->firstItem() + $index }}</td>
                            <td>{{ $reservation->check_in->format('d M Y') }} - {{ $reservation->check_out->format('d M Y') }}</td>
                            <td>{{ $reservation->nama_tamu }}</td>
                            <td>{{ $reservation->room->nama_kamar ?? '-' }}</td>
                            <td>
                                <span class="badge badge-status {{ $reservation->status }}">{{ str_replace('_', ' ', $reservation->status) }}</span>
                            </td>
                            <td>Rp {{ number_format($reservation->subtotal, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($reservation->denda, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($reservation->grandTotal, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">Tidak ada data untuk filter yang dipilih.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white border-0">
            {{ $reservations->links() }}
        </div>
    </div>
@endsection

