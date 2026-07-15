<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MedicineController;

Route::get('/', function () {
    return view('welcome');
});

// Ito ang tamang grupo ng ruta na gagamit ng MedicineController para sa iyong dashboard
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [MedicineController::class, 'index'])->name('dashboard');
    Route::resource('medicines', MedicineController::class)->except(['index', 'show']);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';