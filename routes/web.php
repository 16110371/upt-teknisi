<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicRequestController;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/permintaan', [PublicRequestController::class, 'create'])->name('public-request.create');
Route::post('/permintaan', [PublicRequestController::class, 'store'])->name('public-request.store');
