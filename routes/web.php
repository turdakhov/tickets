<?php

use App\Http\Controllers\Admin\TicketController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('support', [TicketController::class, 'create'])->name('support');
    Route::post('tickets/store', [TicketController::class, 'store'])->name('tickets.store');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
