@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-semibold text-secondary mb-1">Detail Reservasi</h3>
            <p class="text-muted mb-0">Informasi lengkap tamu dan status menginap.</p>
        </div>
        <a href="{{ route('reservations.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card border-0">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0 text-secondary">Data Tamu</h5>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-4">Nama Tamu</dt>
                        <dd class="col-sm-8">{{ $reservation->nama_tamu }}</dd>

                        <dt class="col-sm-4">No. Identitas</dt>
                        <dd class="col-sm-8">{{ $reservation->no_identitas }}</dd>

                        <dt class="col-sm-4">Jumlah Tamu</dt>
                        <dd class="col-sm-8">{{ $reservation->jumlah_tamu }} orang</dd>

                        <dt class="col-sm-4">Dibuat oleh</dt>
                        <dd class="col-sm-8">{{ $reservation->user->name ?? '-' }}</dd>
                    </dl>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card border-0">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0 text-secondary">Informasi Menginap</h5>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-4">Kamar</dt>
                        <dd class="col-sm-8">{{ $reservation->room->nama_kamar ?? '-' }} ({{ $reservation->room->roomType->nama_tipe ?? '-' }})</dd>

                        <dt class="col-sm-4">Check-In</dt>
                        <dd class="col-sm-8">{{ $reservation->check_in->format('d M Y') }}</dd>

                        <dt class="col-sm-4">Check-Out</dt>
                        <dd class="col-sm-8">{{ $reservation->check_out->format('d M Y') }}</dd>

                        <dt class="col-sm-4">Status</dt>
                        <dd class="col-sm-8">
                            <span class="badge badge-status {{ $reservation->status }}">
                                {{ str_replace('_', ' ', $reservation->status) }}
                            </span>
                        </dd>

                        <dt class="col-sm-4">Subtotal</dt>
                        <dd class="col-sm-8">Rp {{ number_format($reservation->subtotal, 0, ',', '.') }}</dd>

                        <dt class="col-sm-4">Denda</dt>
                        <dd class="col-sm-8">Rp {{ number_format($reservation->denda, 0, ',', '.') }}</dd>

                        <dt class="col-sm-4">Total</dt>
                        <dd class="col-sm-8 fw-semibold">Rp {{ number_format($reservation->grandTotal, 0, ',', '.') }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 mt-4">
        <div class="card-body d-flex flex-wrap gap-2">
            @if ($reservation->status === 'menginap')
                <form action="{{ route('reservations.request-housekeeping', $reservation) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-warning text-dark">
                        <i class="fas fa-bell me-2"></i> Ajukan Pengecekan Housekeeping
                    </button>
                </form>
            @endif

            @if ($reservation->status === 'menunggu_pengecekan')
                <div class="alert alert-info mb-0 flex-grow-1">
                    <i class="fas fa-info-circle me-2"></i>
                    Menunggu konfirmasi housekeeping.
                    @if ($reservation->housekeepingCheck)
                        Status: <strong>{{ strtoupper(str_replace('_', ' ', $reservation->housekeepingCheck->status)) }}</strong>
                        @if ($reservation->housekeepingCheck->catatan)
                            <br>Catatan: {{ $reservation->housekeepingCheck->catatan }}
                        @endif
                    @endif
                </div>

                @if (optional($reservation->housekeepingCheck)->status === 'bersih')
                    <a href="{{ route('reservations.checkout.form', $reservation) }}" class="btn btn-success">
                        <i class="fas fa-check-circle me-2"></i>Selesaikan Check-Out
                    </a>
                @endif
            @endif

            @if ($reservation->status === 'selesai')
                <a href="{{ route('reservations.receipt', $reservation) }}" class="btn btn-outline-secondary">
                    <i class="fas fa-print me-2"></i> Cetak Nota
                </a>
            @endif
        </div>
    </div>
@endsection

