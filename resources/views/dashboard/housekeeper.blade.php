@extends('layouts.app')

@section('content')
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-secondary">
                        <i class="fas fa-broom me-2 text-primary"></i>Kamar Menunggu Pengecekan
                    </h5>
                    <a href="{{ route('housekeeping.index') }}" class="btn btn-sm btn-outline-primary">Kelola</a>
                </div>
                <div class="card-body">
                    @forelse ($pendingChecks as $reservation)
                        <div class="border-bottom pb-3 mb-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="fw-semibold mb-1">{{ $reservation->room->nama_kamar }}</h6>
                                    <div class="text-muted small mb-1">
                                        Tamu: {{ $reservation->nama_tamu }} • Check-out {{ $reservation->check_out->format('d M Y') }}
                                    </div>
                                    @if($reservation->housekeepingCheck)
                                        <span class="badge bg-{{ $reservation->housekeepingCheck->status === 'bersih' ? 'success' : 'warning' }} text-uppercase">
                                            {{ str_replace('_', ' ', $reservation->housekeepingCheck->status) }}
                                        </span>
                                    @else
                                        <span class="badge bg-warning text-dark">Belum dicek</span>
                                    @endif
                                </div>
                                <div class="d-flex flex-column gap-2">
                                    <form action="{{ route('housekeeping.update', $reservation) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="bersih">
                                        <button type="submit" class="btn btn-sm btn-success">
                                            <i class="fas fa-check me-1"></i> Tandai Bersih
                                        </button>
                                    </form>
                                    <button type="button" class="btn btn-sm btn-warning text-dark" data-bs-toggle="modal" data-bs-target="#catatanModal{{ $reservation->id }}">
                                        <i class="fas fa-tools me-1"></i> Perlu Perbaikan
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="catatanModal{{ $reservation->id }}" tabindex="-1" aria-labelledby="catatanModalLabel{{ $reservation->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <form action="{{ route('housekeeping.update', $reservation) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="butuh_perbaikan">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="catatanModalLabel{{ $reservation->id }}">Catatan Perbaikan</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="catatan{{ $reservation->id }}" class="form-label">Detail Perbaikan</label>
                                                <textarea class="form-control" id="catatan{{ $reservation->id }}" name="catatan" rows="4" required>{{ old('catatan') }}</textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-warning text-dark">Kirim Catatan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted mb-0">Belum ada kamar yang menunggu pengecekan.</p>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0 text-secondary">
                        <i class="fas fa-tools me-2 text-danger"></i>Kamar Maintenance
                    </h5>
                </div>
                <div class="card-body">
                    @forelse ($roomsNeedingAttention as $room)
                        <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                            <div>
                                <h6 class="mb-1">{{ $room->nama_kamar }}</h6>
                                <div class="text-muted small">{{ $room->roomType->nama_tipe }}</div>
                            </div>
                            <span class="status-room maintenance"><i class="fas fa-circle"></i> Maintenance</span>
                        </div>
                    @empty
                        <p class="text-muted mb-0">Tidak ada kamar dalam kondisi maintenance.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection

