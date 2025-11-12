<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Room;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function reservations(Request $request): View
    {
        $filters = $request->only('status', 'from', 'to');

        $reservations = $this->reservationQuery($request)
            ->orderByDesc('check_in')
            ->paginate(20)
            ->withQueryString();

        return view('reports.reservations', compact('reservations', 'filters'));
    }

    public function reservationsPdf(Request $request)
    {
        $reservations = $this->reservationQuery($request)
            ->orderBy('check_in')
            ->get();

        $pdf = Pdf::loadView('reports.pdf.reservations', [
            'reservations' => $reservations,
            'filters' => $request->only('status', 'from', 'to'),
        ]);

        return $pdf->stream('laporan-reservasi.pdf');
    }

    public function finance(Request $request): View
    {
        $filters = $request->only('from', 'to');

        $transactions = $this->transactionQuery($request)
            ->orderByDesc('tanggal')
            ->paginate(20)
            ->withQueryString();

        $summary = $this->financeSummary($request);

        return view('reports.finance', compact('transactions', 'filters', 'summary'));
    }

    public function financePdf(Request $request)
    {
        $transactions = $this->transactionQuery($request)
            ->orderBy('tanggal')
            ->get();

        $summary = $this->financeSummary($request);

        $pdf = Pdf::loadView('reports.pdf.finance', [
            'transactions' => $transactions,
            'summary' => $summary,
            'filters' => $request->only('from', 'to'),
        ]);

        return $pdf->stream('laporan-keuangan.pdf');
    }

    public function rooms(Request $request): View
    {
        $rooms = Room::with(['roomType', 'reservations' => fn ($query) => $query->orderByDesc('created_at')->limit(5)])
            ->orderBy('kode_kamar')
            ->paginate(20);

        return view('reports.rooms', compact('rooms'));
    }

    public function roomsPdf()
    {
        $rooms = Room::with(['roomType', 'reservations' => fn ($query) => $query->orderByDesc('created_at')->limit(5)])
            ->orderBy('kode_kamar')
            ->get();

        $pdf = Pdf::loadView('reports.pdf.rooms', compact('rooms'));

        return $pdf->stream('laporan-kamar.pdf');
    }

    protected function reservationQuery(Request $request): Builder
    {
        return Reservation::with(['room.roomType', 'user', 'housekeepingCheck.housekeeper'])
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->when($request->filled('from'), fn ($query) => $query->whereDate('check_in', '>=', $request->date('from')))
            ->when($request->filled('to'), fn ($query) => $query->whereDate('check_out', '<=', $request->date('to')));
    }

    protected function transactionQuery(Request $request): Builder
    {
        return Transaction::with('reservation.room')
            ->when($request->filled('from'), fn ($query) => $query->whereDate('tanggal', '>=', $request->date('from')))
            ->when($request->filled('to'), fn ($query) => $query->whereDate('tanggal', '<=', $request->date('to')));
    }

    protected function financeSummary(Request $request): array
    {
        $totals = $this->transactionQuery($request)
            ->selectRaw('SUM(CASE WHEN tipe = "pemasukan" THEN nominal ELSE 0 END) as pemasukan')
            ->selectRaw('SUM(CASE WHEN tipe = "pengeluaran" THEN nominal ELSE 0 END) as pengeluaran')
            ->first();

        $pemasukan = (float) ($totals?->pemasukan ?? 0);
        $pengeluaran = (float) ($totals?->pengeluaran ?? 0);

        return [
            'pemasukan' => $pemasukan,
            'pengeluaran' => $pengeluaran,
            'saldo' => $pemasukan - $pengeluaran,
        ];
    }
}
