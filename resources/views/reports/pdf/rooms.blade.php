<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Kamar</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #2c3e50; }
        h1 { text-align: center; margin-bottom: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #bdc3c7; padding: 6px; text-align: left; vertical-align: top; }
        th { background-color: #2c3e50; color: #fff; }
        ul { margin: 0; padding-left: 16px; }
        .footer { margin-top: 20px; text-align: right; font-size: 11px; }
    </style>
</head>
<body>
    <h1>Laporan Kamar VOKS-INN</h1>
    <table>
        <thead>
            <tr>
                <th>No</th>
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
                    <td>{{ $room->kode_kamar }}</td>
                    <td>{{ $room->nama_kamar }}</td>
                    <td>{{ $room->roomType->nama_tipe }}</td>
                    <td>{{ strtoupper($room->status) }}</td>
                    <td>Rp {{ number_format($room->harga_per_malam, 0, ',', '.') }}</td>
                    <td>
                        @if ($room->reservations->isNotEmpty())
                            <ul>
                                @foreach ($room->reservations as $reservation)
                                    <li>{{ $reservation->nama_tamu }} ({{ $reservation->check_in->format('d-m') }} s/d {{ $reservation->check_out->format('d-m-Y') }})</li>
                                @endforeach
                            </ul>
                        @else
                            Tidak ada histori
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="footer">
        Dicetak pada {{ now()->format('d/m/Y H:i') }}
    </div>
</body>
</html>

