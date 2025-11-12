@extends('layouts.app')

@section('content')
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
        <div>
            <h3 class="fw-semibold text-secondary mb-1">Laporan Kamar</h3>
            <p class="text-muted mb-0">Status kamar beserta histori reservasi terbaru.</p>
        </div>
        <a href="{{ route('reports.rooms.pdf') }}" target="_blank" class="btn btn-outline-primary">
            <i class="fas fa-file-pdf me-2"></i>Export PDF
        </a>
    </div>

    <div class="card border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Kode</th>
                        <th>Nama Kamar</th>
                        <th>Tipe</th>
                        <th>Status</th>
                        <th>Harga / Malam</th>
                        <th>Reservasi Terakhir</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rooms as $index => $room)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td class="fw-semibold">{{ $room->kode_kamar }}</td>
                            <td>{{ $room->nama_kamar }}</td>
                            <td>{{ $room->roomType->nama_tipe }}</td>
                            <td>
                                <span class="status-room {{ $room->status }}">
                                    <i class="fas fa-circle"></i> {{ ucfirst($room->status) }}
                                </span>
                            </td>
                            <td>Rp {{ number_format($room->harga_per_malam, 0, ',', '.') }}</td>
                            <td>
                                @if ($room->reservations->isNotEmpty())
                                    <ul class="list-unstyled mb-0">
                                        @foreach ($room->reservations as $reservation)
                                            <li class="mb-1">
                                                <strong>{{ $reservation->nama_tamu }}</strong>
                                                <br><small class="text-muted">{{ $reservation->check_in->format('d M Y') }} - {{ $reservation->check_out->format('d M Y') }}</small>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <span class="text-muted">Belum ada histori</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white border-0">
            {{ $rooms->links() }}
        </div>
    </div>
@endsection

