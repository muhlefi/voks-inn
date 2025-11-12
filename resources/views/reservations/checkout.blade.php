@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-semibold text-secondary mb-1">Proses Check-Out</h3>
            <p class="text-muted mb-0">Finalisasi pembayaran dan status kamar.</p>
        </div>
        <a href="{{ route('reservations.show', $reservation) }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card border-0">
                <div class="card-body">
                    <h5 class="fw-semibold text-secondary mb-3">Ringkasan Menginap</h5>
                    <table class="table table-borderless mb-0">
                        <tr>
                            <th class="w-50">Nama Tamu</th>
                            <td>{{ $reservation->nama_tamu }}</td>
                        </tr>
                        <tr>
                            <th>Kamar</th>
                            <td>{{ $reservation->room->nama_kamar }} ({{ $reservation->room->roomType->nama_tipe }})</td>
                        </tr>
                        <tr>
                            <th>Durasi Menginap</th>
                            <td>{{ $reservation->lama_inap }} malam</td>
                        </tr>
                        <tr>
                            <th>Tanggal Check-In</th>
                            <td>{{ $reservation->check_in->format('d M Y') }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Check-Out</th>
                            <td>{{ $reservation->check_out->format('d M Y') }}</td>
                        </tr>
                        <tr>
                            <th>Status Housekeeping</th>
                            <td>
                                <span class="badge bg-success text-uppercase">Bersih</span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card border-0">
                <div class="card-body">
                    <h5 class="fw-semibold text-secondary mb-3">Total Pembayaran</h5>
                    <ul class="list-group list-group-flush mb-3">
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Harga / malam</span>
                            <strong>Rp {{ number_format($reservation->room->harga_per_malam, 0, ',', '.') }}</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Durasi</span>
                            <strong>{{ $reservation->lama_inap }} malam</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Subtotal</span>
                            <strong>Rp {{ number_format($reservation->subtotal, 0, ',', '.') }}</strong>
                        </li>
                    </ul>

                    <form action="{{ route('reservations.checkout', $reservation) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="denda" class="form-label">Denda (opsional)</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="denda" id="denda" class="form-control" min="0" step="1000" value="{{ old('denda', $reservation->denda) }}">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="keterangan" class="form-label">Keterangan Nota</label>
                            <input type="text" name="keterangan" id="keterangan" class="form-control" value="{{ old('keterangan', 'Pembayaran '.$reservation->nama_tamu) }}">
                        </div>
                        <button type="submit" class="btn btn-success w-100">
                            <i class="fas fa-check-circle me-2"></i>Selesaikan Check-Out
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

