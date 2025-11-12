<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Reservasi</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #2c3e50; }
        h1 { text-align: center; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #bdc3c7; padding: 6px; text-align: left; }
        th { background-color: #2c3e50; color: #fff; }
        .footer { margin-top: 20px; text-align: right; font-size: 11px; }
    </style>
</head>
<body>
    <h1>Laporan Reservasi VOKS-INN</h1>
    <p><strong>Periode:</strong>
        {{ $filters['from'] ?? 'Semua' }} s/d {{ $filters['to'] ?? 'Semua' }}
        • <strong>Status:</strong> {{ $filters['status'] ?? 'Semua' }}
    </p>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Nama Tamu</th>
                <th>Kamar</th>
                <th>Status</th>
                <th>Subtotal</th>
                <th>Denda</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reservations as $index => $reservation)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $reservation->check_in->format('d-m-Y') }} - {{ $reservation->check_out->format('d-m-Y') }}</td>
                    <td>{{ $reservation->nama_tamu }}</td>
                    <td>{{ $reservation->room->nama_kamar ?? '-' }}</td>
                    <td>{{ strtoupper(str_replace('_', ' ', $reservation->status)) }}</td>
                    <td>Rp {{ number_format($reservation->subtotal, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($reservation->denda, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($reservation->grandTotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="footer">
        Dicetak pada {{ now()->format('d/m/Y H:i') }}
    </div>
</body>
</html>

