<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicRequestController;
use App\Http\Controllers\QueueController;


Route::get('/', function () {
    return view('index');
});


Route::get('/permintaan', [PublicRequestController::class, 'create'])->name('public-request.create');
Route::post('/permintaan', [PublicRequestController::class, 'store'])->name('public-request.store');

Route::get('/antrian', [QueueController::class, 'index'])
    ->name('public.queue');
