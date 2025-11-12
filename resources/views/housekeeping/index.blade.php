@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-semibold text-secondary mb-1">Housekeeping</h3>
            <p class="text-muted mb-0">Daftar kamar yang menunggu pengecekan.</p>
        </div>
    </div>

    <div class="card border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Kamar</th>
                        <th>Nama Tamu</th>
                        <th>Check-Out</th>
                        <th>Status Housekeeping</th>
                        <th>Catatan</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($reservations as $index => $reservation)
                        <tr>
                            <td>{{ $reservations->firstItem() + $index }}</td>
                            <td class="fw-semibold">{{ $reservation->room->nama_kamar }}</td>
                            <td>{{ $reservation->nama_tamu }}</td>
                            <td>{{ $reservation->check_out->format('d M Y') }}</td>
                            <td>
                                @if ($reservation->housekeepingCheck)
                                    <span class="badge bg-{{ $reservation->housekeepingCheck->status === 'bersih' ? 'success' : 'warning' }}">
                                        {{ strtoupper(str_replace('_', ' ', $reservation->housekeepingCheck->status)) }}
                                    </span>
                                @else
                                    <span class="badge bg-warning text-dark">Belum dicek</span>
                                @endif
                            </td>
                            <td>{{ $reservation->housekeepingCheck->catatan ?? '-' }}</td>
                            <td class="text-end">
                                <div class="btn-group">
                                    <form action="{{ route('housekeeping.update', $reservation) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="bersih">
                                        <button type="submit" class="btn btn-sm btn-success">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    <button class="btn btn-sm btn-warning text-dark" data-bs-toggle="modal" data-bs-target="#catatanModal{{ $reservation->id }}">
                                        <i class="fas fa-tools"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <div class="modal fade" id="catatanModal{{ $reservation->id }}" tabindex="-1" aria-labelledby="catatanModalLabel{{ $reservation->id }}" aria-hidden="true">
                            <div class="modal-dialog">
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
                                                <label for="catatan{{ $reservation->id }}" class="form-label">Detail</label>
                                                <textarea class="form-control" id="catatan{{ $reservation->id }}" name="catatan" rows="4" required>{{ old('catatan') }}</textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-warning text-dark">Simpan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Tidak ada kamar yang menunggu pengecekan.</td>
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

