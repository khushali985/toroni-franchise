<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\FranchiseController;
use App\Http\Controllers\FranchisePartnerController;
use App\Http\Controllers\ReservationController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/about', [AboutController::class, 'index'])->name('about');

Route::get('/menu', [MenuController::class, 'index'])->name('menu');

Route::get('/franchise', [FranchiseController::class, 'index'])->name('franchise');

Route::post('/franchise/partner', [FranchisePartnerController::class, 'store'])->name('franchise.partner.store');

Route::get('/order', [OrderController::class, 'index'])->name('order');

Route::post('/order', [OrderController::class, 'store']);

Route::get('/reservation', [ReservationController::class, 'index'])->name('reservation');

Route::post('/reservation', [ReservationController::class, 'store'])->name('reservation.store');

