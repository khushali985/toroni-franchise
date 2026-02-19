<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\FranchiseController;
use App\Http\Controllers\FranchisePartnerController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AuthController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/about', [AboutController::class, 'index'])->name('about');

Route::get('/menu', [MenuController::class, 'index'])->name('menu');

Route::get('/franchise', [FranchiseController::class, 'index'])->name('franchise');

Route::post('/franchise/partner', [FranchisePartnerController::class, 'store'])->name('franchise.partner.store');

Route::get('/order', [OrderController::class, 'index'])->name('order');

Route::post('/order', [OrderController::class, 'store']);

Route::get('/reservation', [ReservationController::class, 'index'])->name('reservation');

Route::post('/reservation', [ReservationController::class, 'store'])->name('reservation.store');

Route::post('/reservation/check-availability', [ReservationController::class, 'checkAvailability'])
    ->name('reservation.checkAvailability');


Route::get('/reservation/tables/{franchise}', [ReservationController::class, 'getTables']);

Route::prefix('admin')->group(function () {

    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('admin.login');

    Route::post('/login', [AuthController::class, 'login'])
        ->name('admin.login.submit');

    Route::middleware('admin.auth')->group(function () {

        Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])
            ->name('admin.dashboard');

        Route::post('/logout', [AuthController::class, 'logout'])
            ->name('admin.logout');

    
        // Update reservation status
        Route::patch('/reservation/{reservation}/status',
            [DashboardController::class, 'updateReservationStatus'])
            ->name('admin.reservation.status');

        // Delete reservation
        Route::delete('/reservation/{reservation}',
            [DashboardController::class, 'deleteReservation'])
            ->name('admin.reservation.delete');

        // (Optional) Export PDF
        Route::get('/reservation/export/pdf',
            [DashboardController::class, 'exportReservationPdf'])
            ->name('admin.reservation.export.pdf');

        // (Optional) Export Excel
        Route::get('/reservation/export/excel',
            [DashboardController::class, 'exportReservationExcel'])
            ->name('admin.reservation.export.excel');

            Route::post('/reservation/store',
    [DashboardController::class, 'storeReservation'])
    ->name('admin.reservation.store');

    });

});



