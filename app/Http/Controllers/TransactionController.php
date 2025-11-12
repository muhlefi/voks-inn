<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransactionRequest;
use App\Models\Reservation;
use App\Models\Transaction;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->only(['tipe', 'from', 'to']);

        $baseQuery = Transaction::with(['reservation.room'])
            ->when($request->filled('tipe'), fn ($query) => $query->where('tipe', $request->string('tipe')))
            ->when($request->filled('from'), fn ($query) => $query->whereDate('tanggal', '>=', $request->date('from')))
            ->when($request->filled('to'), fn ($query) => $query->whereDate('tanggal', '<=', $request->date('to')));

        $transactions = (clone $baseQuery)
            ->orderByDesc('tanggal')
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        $totals = (clone $baseQuery)
            ->selectRaw('SUM(CASE WHEN tipe = "pemasukan" THEN nominal ELSE 0 END) as total_pemasukan')
            ->selectRaw('SUM(CASE WHEN tipe = "pengeluaran" THEN nominal ELSE 0 END) as total_pengeluaran')
            ->first();

        $summary = [
            'pemasukan' => (float) ($totals?->total_pemasukan ?? 0),
            'pengeluaran' => (float) ($totals?->total_pengeluaran ?? 0),
        ];
        $summary['saldo'] = $summary['pemasukan'] - $summary['pengeluaran'];

        return view('finance.transactions.index', compact('transactions', 'summary', 'filters'));
    }

    public function create(): View
    {
        $reservations = Reservation::with('room')
            ->orderByDesc('check_out')
            ->limit(50)
            ->get();

        return view('finance.transactions.create', compact('reservations'));
    }

    public function store(StoreTransactionRequest $request): RedirectResponse
    {
        $data = $request->validated();

        Transaction::create($data);

        return redirect()
            ->route('transactions.index')
            ->with('success', 'Transaksi berhasil dicatat.');
    }

    public function destroy(Transaction $transaction): RedirectResponse
    {
        if ($transaction->reservation_id) {
            return back()->with('error', 'Transaksi yang berasal dari reservasi tidak dapat dihapus.');
        }

        $transaction->delete();

        return redirect()
            ->route('transactions.index')
            ->with('success', 'Transaksi berhasil dihapus.');
    }
}
