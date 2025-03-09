<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RaffleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/rifas/create', [RaffleController::class, 'create'])->name('rifas.create');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/raffles/create', [RaffleController::class, 'create'])->name('raffles.create');
    Route::post('/raffles', [RaffleController::class, 'store'])->name('raffles.store');
});

require __DIR__ . '/auth.php';
