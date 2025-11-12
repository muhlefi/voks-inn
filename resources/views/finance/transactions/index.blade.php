@extends('layouts.app')

@section('content')
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
        <div>
            <h3 class="fw-semibold text-secondary mb-1">Catatan Keuangan</h3>
            <p class="text-muted mb-0">Pantau pemasukan dan pengeluaran hotel.</p>
        </div>
        <a href="{{ route('transactions.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Tambah Transaksi
        </a>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 bg-success text-white">
                <div class="card-body">
                    <p class="text-uppercase small mb-1">Total Pemasukan</p>
                    <h4 class="fw-bold mb-0">Rp {{ number_format($summary['pemasukan'], 0, ',', '.') }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 bg-danger text-white">
                <div class="card-body">
                    <p class="text-uppercase small mb-1">Total Pengeluaran</p>
                    <h4 class="fw-bold mb-0">Rp {{ number_format($summary['pengeluaran'], 0, ',', '.') }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 bg-primary text-white">
                <div class="card-body">
                    <p class="text-uppercase small mb-1">Saldo</p>
                    <h4 class="fw-bold mb-0">Rp {{ number_format($summary['saldo'], 0, ',', '.') }}</h4>
                </div>
            </div>
        </div>
    </div>

    <form method="GET" class="card border-0 mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label small text-uppercase text-muted" for="from">Dari</label>
                    <input type="date" name="from" id="from" class="form-control" value="{{ $filters['from'] ?? '' }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-uppercase text-muted" for="to">Sampai</label>
                    <input type="date" name="to" id="to" class="form-control" value="{{ $filters['to'] ?? '' }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-uppercase text-muted" for="tipe">Tipe</label>
                    <select name="tipe" id="tipe" class="form-select">
                        <option value="">Semua</option>
                        <option value="pemasukan" {{ ($filters['tipe'] ?? '') === 'pemasukan' ? 'selected' : '' }}>Pemasukan</option>
                        <option value="pengeluaran" {{ ($filters['tipe'] ?? '') === 'pengeluaran' ? 'selected' : '' }}>Pengeluaran</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2 align-items-end">
                    <button type="submit" class="btn btn-outline-primary flex-grow-1">
                        <i class="fas fa-filter me-2"></i>Filter
                    </button>
                    <a href="{{ route('transactions.index') }}" class="btn btn-outline-secondary">
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
                        <th>Tipe</th>
                        <th>Nominal</th>
                        <th>Keterangan</th>
                        <th>Reservasi</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transactions as $index => $transaction)
                        <tr>
                            <td>{{ $transactions->firstItem() + $index }}</td>
                            <td>{{ \Carbon\Carbon::parse($transaction->tanggal)->format('d M Y') }}</td>
                            <td>
                                <span class="badge {{ $transaction->tipe === 'pemasukan' ? 'bg-success' : 'bg-danger' }}">
                                    {{ strtoupper($transaction->tipe) }}
                                </span>
                            </td>
                            <td>Rp {{ number_format($transaction->nominal, 0, ',', '.') }}</td>
                            <td>{{ $transaction->keterangan }}</td>
                            <td>
                                @if ($transaction->reservation)
                                    <a href="{{ route('reservations.show', $transaction->reservation) }}" class="btn btn-sm btn-outline-secondary">
                                        Lihat Reservasi
                                    </a>
                                @else
                                    <span class="text-muted">Manual</span>
                                @endif
                            </td>
                            <td class="text-end">
                                @if (! $transaction->reservation_id)
                                    <form action="{{ route('transactions.destroy', $transaction) }}" method="POST" onsubmit="return confirm('Hapus transaksi ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                @else
                                    <span class="text-muted small">Otomatis</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Belum ada catatan transaksi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white border-0">
            {{ $transactions->links() }}
        </div>
    </div>
@endsection

