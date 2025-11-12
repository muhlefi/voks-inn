@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-semibold text-secondary mb-1">Nota Pembayaran</h3>
            <p class="text-muted mb-0">Cetak bukti pembayaran untuk tamu.</p>
        </div>
        <div class="d-flex gap-2">
            <button onclick="window.print()" class="btn btn-outline-secondary">
                <i class="fas fa-print me-2"></i>Print Nota
            </button>
            <a href="{{ route('reservations.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>

    <div class="card border-0 receipt-card">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="fw-bold mb-0 text-primary">VOKS-INN HOTEL</h4>
                    <small class="text-muted">Jl. Mawar No. 10, Kota Bandung • Telp 022-1234567</small>
                </div>
                <div class="text-end">
                    <div class="text-uppercase text-muted small">Kode Reservasi</div>
                    <h5 class="fw-semibold mb-0">RES-{{ str_pad($reservation->id, 5, '0', STR_PAD_LEFT) }}</h5>
                    <small class="text-muted">Tanggal: {{ now()->format('d M Y H:i') }}</small>
                </div>
            </div>

            <hr>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <h6 class="text-uppercase text-muted small">Data Tamu</h6>
                    <p class="mb-1"><strong>Nama:</strong> {{ $reservation->nama_tamu }}</p>
                    <p class="mb-1"><strong>No. Identitas:</strong> {{ $reservation->no_identitas }}</p>
                    <p class="mb-0"><strong>Jumlah Tamu:</strong> {{ $reservation->jumlah_tamu }}</p>
                </div>
                <div class="col-md-6 mb-3">
                    <h6 class="text-uppercase text-muted small">Informasi Kamar</h6>
                    <p class="mb-1"><strong>Kamar:</strong> {{ $reservation->room->nama_kamar }} ({{ $reservation->room->kode_kamar }})</p>
                    <p class="mb-1"><strong>Tipe:</strong> {{ $reservation->room->roomType->nama_tipe }}</p>
                    <p class="mb-0"><strong>Harga / Malam:</strong> Rp {{ number_format($reservation->room->harga_per_malam, 0, ',', '.') }}</p>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-6 mb-3">
                    <h6 class="text-uppercase text-muted small">Durasi Menginap</h6>
                    <p class="mb-1"><strong>Check-In:</strong> {{ $reservation->check_in->format('d M Y') }}</p>
                    <p class="mb-1"><strong>Check-Out:</strong> {{ $reservation->check_out->format('d M Y') }}</p>
                    <p class="mb-0"><strong>Total Malam:</strong> {{ $reservation->lama_inap }} malam</p>
                </div>
                <div class="col-md-6">
                    <h6 class="text-uppercase text-muted small">Ringkasan Pembayaran</h6>
                    <table class="table table-borderless mb-0">
                        <tr>
                            <th>Subtotal</th>
                            <td class="text-end">Rp {{ number_format($reservation->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th>Denda</th>
                            <td class="text-end">Rp {{ number_format($reservation->denda, 0, ',', '.') }}</td>
                        </tr>
                        <tr class="table-light">
                            <th>Total Bayar</th>
                            <td class="text-end fw-bold">Rp {{ number_format($reservation->grandTotal, 0, ',', '.') }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="mt-4">
                <h6 class="text-uppercase text-muted small">Keterangan</h6>
                <p class="mb-0">{{ $reservation->transactions()->latest()->first()->keterangan ?? 'Pembayaran reservasi VOKS-INN' }}</p>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-5">
                <div>
                    <p class="mb-1 text-muted small">Kasir</p>
                    <h6 class="fw-semibold mb-0">{{ auth()->user()->name }}</h6>
                </div>
                <div class="text-end">
                    <p class="mb-1 text-muted small">Tanda Tangan</p>
                    <div class="signature-line"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            .receipt-card, .receipt-card * {
                visibility: visible;
            }
            .receipt-card {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                box-shadow: none !important;
            }
        }

        .signature-line {
            width: 200px;
            border-bottom: 1px solid #ccc;
            height: 40px;
        }
    </style>
@endpush

