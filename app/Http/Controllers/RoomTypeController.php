<?php

namespace App\Http\Controllers;

use App\Models\RoomType;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RoomTypeController extends Controller
{
    public function index(): View
    {
        $roomTypes = RoomType::query()
            ->withCount('rooms')
            ->orderBy('nama_tipe')
            ->paginate(10);

        return view('master.room-types.index', compact('roomTypes'));
    }

    public function create(): View
    {
        return view('master.room-types.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_tipe' => ['required', 'string', 'max:100', 'unique:room_types,nama_tipe'],
            'fasilitas' => ['required', 'string'],
        ]);

        RoomType::create($validated);

        return redirect()
            ->route('room-types.index')
            ->with('success', 'Tipe kamar berhasil ditambahkan.');
    }

    public function edit(RoomType $roomType): View
    {
        return view('master.room-types.edit', compact('roomType'));
    }

    public function update(Request $request, RoomType $roomType): RedirectResponse
    {
        $validated = $request->validate([
            'nama_tipe' => ['required', 'string', 'max:100', 'unique:room_types,nama_tipe,'.$roomType->id],
            'fasilitas' => ['required', 'string'],
        ]);

        $roomType->update($validated);

        return redirect()
            ->route('room-types.index')
            ->with('success', 'Tipe kamar berhasil diperbarui.');
    }

    public function destroy(RoomType $roomType): RedirectResponse
    {
        if ($roomType->rooms()->exists()) {
            return redirect()
                ->route('room-types.index')
                ->with('error', 'Tidak dapat menghapus tipe kamar yang masih memiliki kamar.');
        }

        $roomType->delete();

        return redirect()
            ->route('room-types.index')
            ->with('success', 'Tipe kamar berhasil dihapus.');
    }
}
