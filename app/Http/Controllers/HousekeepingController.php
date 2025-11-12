<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class HousekeepingController extends Controller
{
    public function index(): View
    {
        $reservations = Reservation::with(['room.roomType', 'housekeepingCheck.housekeeper'])
            ->where('status', 'menunggu_pengecekan')
            ->orderBy('updated_at', 'desc')
            ->paginate(12);

        return view('housekeeping.index', compact('reservations'));
    }

    public function update(Request $request, Reservation $reservation): RedirectResponse
    {
        if ($reservation->status !== 'menunggu_pengecekan') {
            return back()->with('error', 'Reservasi ini tidak memerlukan pengecekan.');
        }

        $validated = $request->validate([
            'status' => ['required', Rule::in(['bersih', 'butuh_perbaikan'])],
            'catatan' => ['nullable', 'string', 'max:500'],
        ]);

        if ($validated['status'] === 'butuh_perbaikan' && empty($validated['catatan'])) {
            return back()
                ->withInput()
                ->with('error', 'Catatan wajib diisi untuk status butuh perbaikan.');
        }

        $reservation->housekeepingCheck()->updateOrCreate(
            ['reservation_id' => $reservation->id],
            [
                'housekeeper_id' => $request->user()->id,
                'status' => $validated['status'],
                'catatan' => $validated['catatan'] ?? null,
            ]
        );

        if ($validated['status'] === 'butuh_perbaikan') {
            $reservation->room?->update(['status' => 'maintenance']);
        }

        return back()->with('success', 'Status housekeeping berhasil diperbarui.');
    }
}
