<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SucursalController;

Route::get('/', function () {
    return view('welcome');
})->middleware(['auth', 'verified']);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::get('/sucursales', [SucursalController::class, 'index'])
    ->name('sucursales.index')
    ->middleware(['auth', 'role:admin']);

Route::post('/sucursales-create', [SucursalController::class, 'create']);
Route::get('/sucursales-get', [SucursalController::class, 'GetSucursales']);