<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('users', UserController::class);
    Route::resource('orders', OrderController::class);
    Route::post('orders/download', [OrderController::class, 'downloadCsv'])->name('order.download');
    Route::post('orders/download-progress', [OrderController::class, 'checkdownloadCsvStatus'])->name('order.download.process');
    Route::post('orders/download-link', [OrderController::class, 'downloadCsvLink'])->name('order.download.link');
});

require __DIR__ . '/auth.php';
