<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoomRequest;
use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class RoomController extends Controller
{
    public function index(): View
    {
        $rooms = Room::with('roomType')
            ->orderBy('kode_kamar')
            ->paginate(12);

        return view('master.rooms.index', compact('rooms'));
    }

    public function create(): View
    {
        $roomTypes = RoomType::orderBy('nama_tipe')->get();

        return view('master.rooms.create', compact('roomTypes'));
    }

    public function store(StoreRoomRequest $request): RedirectResponse
    {
        Room::create($request->validated());

        return redirect()
            ->route('rooms.index')
            ->with('success', 'Kamar berhasil ditambahkan.');
    }

    public function edit(Room $room): View
    {
        $roomTypes = RoomType::orderBy('nama_tipe')->get();

        return view('master.rooms.edit', compact('room', 'roomTypes'));
    }

    public function update(StoreRoomRequest $request, Room $room): RedirectResponse
    {
        $room->update($request->validated());

        return redirect()
            ->route('rooms.index')
            ->with('success', 'Kamar berhasil diperbarui.');
    }

    public function destroy(Room $room): RedirectResponse
    {
        $hasActiveReservation = $room->reservations()
            ->whereIn('status', ['menginap', 'menunggu_pengecekan'])
            ->exists();

        if ($hasActiveReservation) {
            return redirect()
                ->route('rooms.index')
                ->with('error', 'Kamar masih digunakan dalam reservasi aktif.');
        }

        $room->delete();

        return redirect()
            ->route('rooms.index')
            ->with('success', 'Kamar berhasil dihapus.');
    }
}
