<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\FranchiseController;
use App\Http\Controllers\FranchisePartnerController;
use App\Http\Controllers\ReservationController;

Route::get('/', [HomeController::class, 'index']);

Route::get('/about', [AboutController::class, 'index']);

Route::get('/menu', [MenuController::class, 'index']);

Route::get('/franchise', [FranchiseController::class, 'index']);

Route::post('/franchise-partner', [FranchisePartnerController::class, 'store']);

Route::post('/order', [OrderController::class, 'store']);

Route::post('/reservation', [ReservationController::class, 'store']);

Route::get('/order', fn () => view('pages.order'))->name('order');

Route::get('/reservation', fn () => view('pages.reservation'))->name('reservation');

Route::get('/franchise', [FranchiseController::class, 'index'])->name('franchise');

Route::post('/franchise/partner', [FranchisePartnerController::class, 'store'])
    ->name('franchise.partner.store');
