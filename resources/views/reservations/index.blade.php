@extends('layouts.app')

@section('content')
    <div class="d-flex flex-column flex-lg-row justify-content-between gap-3 align-items-lg-center mb-4">
        <div>
            <h3 class="fw-semibold text-secondary mb-1">Daftar Reservasi</h3>
            <p class="text-muted mb-0">Monitoring riwayat check-in dan check-out.</p>
        </div>
        <a href="{{ route('reservations.create') }}" class="btn btn-primary">
            <i class="fas fa-bed me-2"></i>Check-In Baru
        </a>
    </div>

    <form method="GET" class="card border-0 mb-4">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label for="status" class="form-label small text-uppercase text-muted">Status</label>
                    <select name="status" id="status" class="form-select">
                        <option value="">Semua</option>
                        <option value="menginap" {{ ($filters['status'] ?? '') === 'menginap' ? 'selected' : '' }}>Menginap</option>
                        <option value="menunggu_pengecekan" {{ ($filters['status'] ?? '') === 'menunggu_pengecekan' ? 'selected' : '' }}>Menunggu Pengecekan</option>
                        <option value="selesai" {{ ($filters['status'] ?? '') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="from" class="form-label small text-uppercase text-muted">Dari Tanggal</label>
                    <input type="date" name="from" id="from" class="form-control" value="{{ $filters['from'] ?? '' }}">
                </div>
                <div class="col-md-3">
                    <label for="to" class="form-label small text-uppercase text-muted">Sampai Tanggal</label>
                    <input type="date" name="to" id="to" class="form-control" value="{{ $filters['to'] ?? '' }}">
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-outline-primary flex-grow-1">
                        <i class="fas fa-filter me-2"></i>Filter
                    </button>
                    <a href="{{ route('reservations.index') }}" class="btn btn-outline-secondary">
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
                        <th>Nama Tamu</th>
                        <th>Kamar</th>
                        <th>Check-In</th>
                        <th>Check-Out</th>
                        <th>Status</th>
                        <th>Total</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($reservations as $index => $reservation)
                        <tr>
                            <td>{{ $reservations->firstItem() + $index }}</td>
                            <td class="fw-semibold">{{ $reservation->nama_tamu }}</td>
                            <td>{{ $reservation->room->nama_kamar ?? '-' }}</td>
                            <td>{{ $reservation->check_in->format('d M Y') }}</td>
                            <td>{{ $reservation->check_out->format('d M Y') }}</td>
                            <td>
                                <span class="badge badge-status {{ $reservation->status }}">
                                    {{ str_replace('_', ' ', $reservation->status) }}
                                </span>
                            </td>
                            <td>Rp {{ number_format($reservation->grandTotal, 0, ',', '.') }}</td>
                            <td class="text-end">
                                <div class="btn-group">
                                    <a href="{{ route('reservations.show', $reservation) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if ($reservation->status === 'menginap')
                                        <form action="{{ route('reservations.request-housekeeping', $reservation) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-warning">
                                                <i class="fas fa-bell"></i>
                                            </button>
                                        </form>
                                    @elseif ($reservation->status === 'menunggu_pengecekan' && optional($reservation->housekeepingCheck)->status === 'bersih')
                                        <a href="{{ route('reservations.checkout.form', $reservation) }}" class="btn btn-sm btn-outline-success">
                                            <i class="fas fa-check"></i>
                                        </a>
                                    @elseif($reservation->status === 'selesai')
                                        <a href="{{ route('reservations.receipt', $reservation) }}" class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-print"></i>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">Belum ada data reservasi.</td>
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

