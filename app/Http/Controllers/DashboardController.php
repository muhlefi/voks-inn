<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Room;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function superAdmin(): View
    {
        $roomCount = Room::count();
        $guestCount = Reservation::active()->count();
        $userCount = User::count();
        $transactionCount = Transaction::count();

        $monthlyFinance = Transaction::selectRaw('DATE_FORMAT(tanggal, "%Y-%m") as bulan')
            ->selectRaw('SUM(CASE WHEN tipe = "pemasukan" THEN nominal ELSE 0 END) as total_pemasukan')
            ->where('tanggal', '>=', now()->subMonths(11)->startOfMonth())
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        $labels = [];
        $incomeData = [];

        $start = Carbon::now()->subMonths(11)->startOfMonth();

        for ($i = 0; $i < 12; $i++) {
            $month = (clone $start)->addMonths($i);
            $labels[] = $month->format('M Y');
            $record = $monthlyFinance->firstWhere('bulan', $month->format('Y-m'));
            $incomeData[] = $record?->total_pemasukan ? (float) $record->total_pemasukan : 0;
        }

        return view('dashboard.superadmin', [
            'roomCount' => $roomCount,
            'guestCount' => $guestCount,
            'transactionCount' => $transactionCount,
            'userCount' => $userCount,
            'chart' => [
                'labels' => $labels,
                'income' => $incomeData,
            ],
        ]);
    }

    public function admin(): View
    {
        $activeReservations = Reservation::with(['room', 'user'])
            ->where('status', 'menginap')
            ->orderBy('check_in')
            ->get();

        $pendingCheckouts = Reservation::with(['room', 'user', 'housekeepingCheck'])
            ->whereIn('status', ['menginap', 'menunggu_pengecekan'])
            ->orderBy('check_out')
            ->get();

        $availableRooms = Room::with('roomType')
            ->where('status', 'kosong')
            ->orderBy('kode_kamar')
            ->get();

        $todayIncome = Transaction::whereDate('tanggal', now()->toDateString())
            ->where('tipe', 'pemasukan')
            ->sum('nominal');

        return view('dashboard.admin', [
            'activeReservations' => $activeReservations,
            'pendingCheckouts' => $pendingCheckouts,
            'availableRooms' => $availableRooms,
            'todayIncome' => (float) $todayIncome,
        ]);
    }

    public function housekeeper(): View
    {
        $pendingChecks = Reservation::with(['room', 'housekeepingCheck.housekeeper'])
            ->where('status', 'menunggu_pengecekan')
            ->orderBy('updated_at', 'desc')
            ->get();

        $roomsNeedingAttention = Room::with('roomType')
            ->where('status', 'maintenance')
            ->orderBy('kode_kamar')
            ->get();

        return view('dashboard.housekeeper', [
            'pendingChecks' => $pendingChecks,
            'roomsNeedingAttention' => $roomsNeedingAttention,
        ]);
    }
}
