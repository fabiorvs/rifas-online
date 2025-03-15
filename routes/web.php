<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RaffleController;
use App\Http\Controllers\RaffleNumberController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/rifa/{identification}', [RaffleController::class, 'show'])->name('raffle.show');
Route::post('/rifa/comprar', [RaffleController::class, 'buyNumbers'])->middleware('auth')->name('raffle.buy');
Route::get('/rifa/checkout/{transactionCode}', [RaffleController::class, 'checkout'])->name('raffle.checkout');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/raffles/create', [RaffleController::class, 'create'])->name('raffles.create');
    Route::post('/raffles', [RaffleController::class, 'store'])->name('raffles.store');
    Route::get('/raffles/{id}/edit', [RaffleController::class, 'edit'])->name('raffles.edit');
    Route::put('/raffles/{id}', [RaffleController::class, 'update'])->name('raffles.update');
    Route::get('/raffles/{id}/overview', [RaffleController::class, 'overview'])->name('raffles.overview');
    Route::get('/raffles/numbers', [RaffleNumberController::class, 'numerosConfirmar'])->name('raffle_numbers.to_confirm');
    Route::post('/raffles/confirm-payment', [RaffleNumberController::class, 'confirmarPagamento'])->name('raffle_numbers.confirm_payment');
    Route::post('/raffles/cancel-reservations', [RaffleNumberController::class, 'cancelarReservas'])
        ->name('raffle_numbers.cancel');
    Route::get('/raffles/{id}/draw', [RaffleController::class, 'drawPage'])->name('raffles.draw');
    Route::post('/raffles/{id}/draw', [RaffleController::class, 'performDraw'])->name('raffles.performDraw');

});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/my-raffles', [RaffleNumberController::class, 'myRaffles'])->name('raffles.my');
    Route::get('/my-raffles/{id}', [RaffleNumberController::class, 'myNumbers'])->name('raffles.my_numbers');
});

require __DIR__ . '/auth.php';
