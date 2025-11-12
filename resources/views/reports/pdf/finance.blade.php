<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Keuangan</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #2c3e50; }
        h1 { text-align: center; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #bdc3c7; padding: 6px; text-align: left; }
        th { background-color: #2c3e50; color: #fff; }
        .summary { margin-top: 15px; }
        .summary table { width: 50%; }
        .footer { margin-top: 20px; text-align: right; font-size: 11px; }
    </style>
</head>
<body>
    <h1>Laporan Keuangan VOKS-INN</h1>
    <p><strong>Periode:</strong> {{ $filters['from'] ?? 'Semua' }} s/d {{ $filters['to'] ?? 'Semua' }}</p>

    <div class="summary">
        <table>
            <tr>
                <th>Total Pemasukan</th>
                <td>Rp {{ number_format($summary['pemasukan'], 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Total Pengeluaran</th>
                <td>Rp {{ number_format($summary['pengeluaran'], 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Saldo</th>
                <td>Rp {{ number_format($summary['saldo'], 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Tipe</th>
                <th>Keterangan</th>
                <th>Nominal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transactions as $index => $transaction)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($transaction->tanggal)->format('d-m-Y') }}</td>
                    <td>{{ strtoupper($transaction->tipe) }}</td>
                    <td>{{ $transaction->keterangan }}</td>
                    <td>Rp {{ number_format($transaction->nominal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada {{ now()->format('d/m/Y H:i') }}
    </div>
</body>
</html>

