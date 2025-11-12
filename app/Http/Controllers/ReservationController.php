<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReservationRequest;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    public function index(Request $request): View
    {
        $reservations = Reservation::with(['room.roomType', 'user', 'housekeepingCheck.housekeeper'])
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->when($request->filled('from'), fn ($query) => $query->whereDate('check_in', '>=', $request->date('from')))
            ->when($request->filled('to'), fn ($query) => $query->whereDate('check_out', '<=', $request->date('to')))
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('reservations.index', [
            'reservations' => $reservations,
            'filters' => $request->only('status', 'from', 'to'),
        ]);
    }

    public function create(): View
    {
        $rooms = Room::with('roomType')
            ->where('status', 'kosong')
            ->orderBy('kode_kamar')
            ->get();

        return view('reservations.create', compact('rooms'));
    }

    public function store(StoreReservationRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $room = Room::findOrFail($validated['room_id']);

        if ($room->status !== 'kosong') {
            return back()
                ->withInput()
                ->with('error', 'Kamar yang dipilih sedang tidak tersedia.');
        }

        $checkIn = Carbon::parse($validated['check_in']);
        $checkOut = Carbon::parse($validated['check_out']);
        $lamaInap = max($checkIn->diffInDays($checkOut), 1);
        $totalHarga = $room->harga_per_malam * $lamaInap;

        $reservation = Reservation::create([
            'user_id' => Auth::id(),
            'room_id' => $room->id,
            'nama_tamu' => $validated['nama_tamu'],
            'no_identitas' => $validated['no_identitas'],
            'check_in' => $checkIn,
            'check_out' => $checkOut,
            'jumlah_tamu' => $validated['jumlah_tamu'],
            'total_harga' => $totalHarga,
            'denda' => 0,
            'status' => 'menginap',
        ]);

        $room->update(['status' => 'terisi']);

        return redirect()
            ->route('reservations.show', $reservation)
            ->with('success', 'Check-in berhasil dibuat.');
    }

    public function show(Reservation $reservation): View
    {
        $reservation->load(['room.roomType', 'user', 'housekeepingCheck.housekeeper']);

        return view('reservations.show', compact('reservation'));
    }

    public function requestHousekeeping(Reservation $reservation): RedirectResponse
    {
        if ($reservation->status !== 'menginap') {
            return back()->with('error', 'Reservasi tidak dapat diajukan untuk pengecekan.');
        }

        $reservation->update(['status' => 'menunggu_pengecekan']);
        $reservation->room?->update(['status' => 'maintenance']);

        return back()->with('success', 'Reservasi telah diajukan untuk pengecekan housekeeping.');
    }

    public function checkoutForm(Reservation $reservation): View|RedirectResponse
    {
        $reservation->load(['room', 'housekeepingCheck']);

        if ($reservation->status !== 'menunggu_pengecekan') {
            return redirect()->route('reservations.show', $reservation)
                ->with('error', 'Reservasi belum siap untuk proses check-out.');
        }

        if ($reservation->housekeepingCheck?->status !== 'bersih') {
            return redirect()->route('reservations.show', $reservation)
                ->with('error', 'Housekeeping belum menandai kamar sebagai bersih.');
        }

        return view('reservations.checkout', compact('reservation'));
    }

    public function checkout(Request $request, Reservation $reservation): RedirectResponse
    {
        if ($reservation->status !== 'menunggu_pengecekan') {
            return back()->with('error', 'Reservasi belum siap untuk check-out.');
        }

        if ($reservation->housekeepingCheck?->status !== 'bersih') {
            return back()->with('error', 'Housekeeping belum menandai kamar sebagai bersih.');
        }

        $data = $request->validate([
            'denda' => ['nullable', 'numeric', 'min:0'],
            'keterangan' => ['nullable', 'string', 'max:255'],
        ]);

        $reservation->denda = $data['denda'] ?? 0;
        $reservation->total_harga = $reservation->subtotal + $reservation->denda;
        $reservation->status = 'selesai';
        $reservation->save();

        if ($reservation->room) {
            $reservation->room->update(['status' => 'kosong']);
        }

        Transaction::create([
            'reservation_id' => $reservation->id,
            'tipe' => 'pemasukan',
            'nominal' => $reservation->grandTotal,
            'keterangan' => $data['keterangan'] ?? 'Pembayaran '.$reservation->nama_tamu,
            'tanggal' => now()->toDateString(),
        ]);

        return redirect()
            ->route('reservations.receipt', $reservation)
            ->with('success', 'Check-out berhasil diproses.');
    }

    public function receipt(Reservation $reservation): View|RedirectResponse
    {
        if ($reservation->status !== 'selesai') {
            return redirect()->route('reservations.show', $reservation)
                ->with('error', 'Nota hanya tersedia setelah check-out selesai.');
        }

        $reservation->loadMissing(['room.roomType', 'user']);

        return view('reservations.receipt', compact('reservation'));
    }
}
