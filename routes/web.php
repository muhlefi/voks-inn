<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HousekeepingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\RoomTypeController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        $role = auth()->user()->role;

        return redirect()->route('dashboard.'.$role);
    })->name('dashboard');

    Route::get('/dashboard/superadmin', [DashboardController::class, 'superAdmin'])
        ->middleware('role:superadmin')
        ->name('dashboard.superadmin');

    Route::get('/dashboard/admin', [DashboardController::class, 'admin'])
        ->middleware('role:superadmin,admin')
        ->name('dashboard.admin');

    Route::get('/dashboard/housekeeper', [DashboardController::class, 'housekeeper'])
        ->middleware('role:superadmin,housekeeper')
        ->name('dashboard.housekeeper');

    Route::middleware('role:superadmin')->group(function () {
        Route::resource('users', UserController::class)->except(['show']);
        Route::resource('room-types', RoomTypeController::class)->except(['show']);
        Route::resource('rooms', RoomController::class)->except(['show']);
    });

    Route::middleware('role:superadmin,admin')->group(function () {
        Route::resource('reservations', ReservationController::class)->only(['index', 'create', 'store', 'show']);
        Route::post('reservations/{reservation}/request-housekeeping', [ReservationController::class, 'requestHousekeeping'])
            ->name('reservations.request-housekeeping');
        Route::get('reservations/{reservation}/checkout', [ReservationController::class, 'checkoutForm'])
            ->name('reservations.checkout.form');
        Route::put('reservations/{reservation}/checkout', [ReservationController::class, 'checkout'])
            ->name('reservations.checkout');
        Route::get('reservations/{reservation}/receipt', [ReservationController::class, 'receipt'])
            ->name('reservations.receipt');

        Route::resource('transactions', TransactionController::class)->only(['index', 'create', 'store', 'destroy']);

        Route::controller(ReportController::class)
            ->prefix('reports')
            ->name('reports.')
            ->group(function () {
                Route::get('reservations', 'reservations')->name('reservations');
                Route::get('reservations/pdf', 'reservationsPdf')->name('reservations.pdf');
                Route::get('finance', 'finance')->name('finance');
                Route::get('finance/pdf', 'financePdf')->name('finance.pdf');
                Route::get('rooms', 'rooms')->name('rooms');
                Route::get('rooms/pdf', 'roomsPdf')->name('rooms.pdf');
            });
    });

    Route::middleware('role:superadmin,housekeeper')->group(function () {
        Route::get('housekeeping', [HousekeepingController::class, 'index'])->name('housekeeping.index');
        Route::put('housekeeping/{reservation}', [HousekeepingController::class, 'update'])->name('housekeeping.update');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
