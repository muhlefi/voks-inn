@extends('layouts.app')

@section('content')
    <div class="row g-4">
        <div class="col-md-3">
            <div class="card h-100 border-0">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-uppercase text-muted small mb-2">Total Kamar</h6>
                            <h3 class="fw-bold mb-0">{{ $roomCount }}</h3>
                        </div>
                        <span class="badge bg-primary rounded-pill">
                            <i class="fas fa-door-closed"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100 border-0">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-uppercase text-muted small mb-2">Tamu Menginap</h6>
                            <h3 class="fw-bold mb-0">{{ $guestCount }}</h3>
                        </div>
                        <span class="badge bg-info rounded-pill">
                            <i class="fas fa-user-friends"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100 border-0">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-uppercase text-muted small mb-2">Transaksi</h6>
                            <h3 class="fw-bold mb-0">{{ $transactionCount }}</h3>
                        </div>
                        <span class="badge bg-success rounded-pill">
                            <i class="fas fa-wallet"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100 border-0">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-uppercase text-muted small mb-2">Pengguna</h6>
                            <h3 class="fw-bold mb-0">{{ $userCount }}</h3>
                        </div>
                        <span class="badge bg-secondary rounded-pill">
                            <i class="fas fa-users"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 mt-4">
        <div class="card-header bg-white border-0 d-flex align-items-center justify-content-between">
            <h5 class="mb-0 text-secondary">Grafik Pemasukan Bulanan</h5>
            <span class="text-muted small">Periode 12 bulan terakhir</span>
        </div>
        <div class="card-body">
            <canvas id="incomeChart" height="120"></canvas>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('incomeChart');

            if (ctx && window.Chart) {
                const chart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: @json($chart['labels']),
                        datasets: [{
                            label: 'Pemasukan',
                            data: @json($chart['income']),
                            fill: true,
                            tension: 0.35,
                            backgroundColor: 'rgba(52, 152, 219, 0.2)',
                            borderColor: '#3498db',
                            borderWidth: 3,
                            pointBackgroundColor: '#2c3e50'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return new Intl.NumberFormat('id-ID', {
                                            style: 'currency',
                                            currency: 'IDR',
                                            minimumFractionDigits: 0
                                        }).format(value);
                                    }
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return new Intl.NumberFormat('id-ID', {
                                            style: 'currency',
                                            currency: 'IDR',
                                            minimumFractionDigits: 0
                                        }).format(context.parsed.y);
                                    }
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
@endpush

