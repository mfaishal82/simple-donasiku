<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DonationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/user/{username}', [DonationController::class, 'index'])->name('donation.index');

Route::post('/donate', [DonationController::class, 'store'])->name('donations.store');

Route::get('/donation/{id}/success', [DonationController::class, 'success'])->name('donations.success');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
