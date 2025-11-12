@extends('layouts.app')

@section('content')
    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card border-0">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0 text-secondary"><i class="fas fa-bed me-2 text-primary"></i>Tamu Menginap</h5>
                </div>
                <div class="card-body">
                    @forelse ($activeReservations as $reservation)
                        <div class="d-flex justify-content-between align-items-start mb-3 pb-3 border-bottom">
                            <div>
                                <h6 class="fw-semibold mb-1">{{ $reservation->nama_tamu }}</h6>
                                <div class="text-muted small">Kamar {{ $reservation->room->kode_kamar }} • Check-out {{ $reservation->check_out->format('d M Y') }}</div>
                            </div>
                            <span class="status-room terisi">
                                <i class="fas fa-circle"></i> Menginap
                            </span>
                        </div>
                    @empty
                        <p class="text-muted mb-0">Belum ada tamu menginap saat ini.</p>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-secondary"><i class="fas fa-door-open me-2 text-warning"></i>Proses Check-Out</h5>
                    <a href="{{ route('reservations.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                </div>
                <div class="card-body">
                    @forelse ($pendingCheckouts as $reservation)
                        <div class="d-flex justify-content-between align-items-start mb-3 pb-3 border-bottom">
                            <div>
                                <h6 class="fw-semibold mb-1">{{ $reservation->nama_tamu }}</h6>
                                <div class="text-muted small mb-1">Kamar {{ $reservation->room->kode_kamar }}</div>
                                <span class="badge badge-status {{ $reservation->status }}">{{ str_replace('_', ' ', $reservation->status) }}</span>
                            </div>
                            <div class="d-flex flex-column align-items-end gap-1">
                                @if ($reservation->status === 'menginap')
                                    <form action="{{ route('reservations.request-housekeeping', $reservation) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-bell me-1"></i> Ajukan Pengecekan
                                        </button>
                                    </form>
                                @elseif ($reservation->status === 'menunggu_pengecekan' && optional($reservation->housekeepingCheck)->status === 'bersih')
                                    <a href="{{ route('reservations.checkout.form', $reservation) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-check-circle me-1"></i> Selesaikan
                                    </a>
                                @else
                                    <span class="text-muted small"><i class="fas fa-broom me-1"></i> Menunggu Housekeeping</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-muted mb-0">Belum ada reservasi yang menunggu check-out.</p>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase text-muted small mb-2">Pendapatan Hari Ini</h6>
                            <h3 class="fw-bold mb-0">Rp {{ number_format($todayIncome, 0, ',', '.') }}</h3>
                        </div>
                        <span class="badge bg-success rounded-pill">
                            <i class="fas fa-money-bill-wave"></i>
                        </span>
                    </div>
                </div>
            </div>

            <div class="card border-0">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0 text-secondary"><i class="fas fa-door-closed me-2 text-success"></i>Kamar Tersedia</h5>
                </div>
                <div class="card-body">
                    @forelse ($availableRooms as $room)
                        <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                            <div>
                                <h6 class="mb-1">{{ $room->nama_kamar }}</h6>
                                <div class="text-muted small">{{ $room->roomType->nama_tipe }}</div>
                            </div>
                            <span class="status-room kosong"><i class="fas fa-circle"></i> Kosong</span>
                        </div>
                    @empty
                        <p class="text-muted mb-0">Semua kamar sedang terisi.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection

