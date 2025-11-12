@extends('layouts.app')

@section('content')
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
        <div>
            <h3 class="fw-semibold text-secondary mb-1">Laporan Keuangan</h3>
            <p class="text-muted mb-0">Rekap pemasukan dan pengeluaran hotel.</p>
        </div>
        <a href="{{ route('reports.finance.pdf', request()->query()) }}" target="_blank" class="btn btn-outline-primary">
            <i class="fas fa-file-pdf me-2"></i>Export PDF
        </a>
    </div>

    <form method="GET" class="card border-0 mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label small text-uppercase text-muted">Dari</label>
                    <input type="date" name="from" class="form-control" value="{{ $filters['from'] ?? '' }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label small text-uppercase text-muted">Sampai</label>
                    <input type="date" name="to" class="form-control" value="{{ $filters['to'] ?? '' }}">
                </div>
                <div class="col-md-4 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-outline-primary flex-grow-1">
                        <i class="fas fa-filter me-2"></i> Terapkan
                    </button>
                    <a href="{{ route('reports.finance') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-undo"></i>
                    </a>
                </div>
            </div>
        </div>
    </form>

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
                    <p class="text-uppercase small mb-1">Saldo Akhir</p>
                    <h4 class="fw-bold mb-0">Rp {{ number_format($summary['saldo'], 0, ',', '.') }}</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tanggal</th>
                        <th>Tipe</th>
                        <th>Keterangan</th>
                        <th>Nominal</th>
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
                            <td>{{ $transaction->keterangan }}</td>
                            <td>Rp {{ number_format($transaction->nominal, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Tidak ada data transaksi.</td>
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

