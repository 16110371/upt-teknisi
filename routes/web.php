<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicRequestController;
use App\Http\Controllers\QueueController;
use App\Models\FcmToken;


Route::get('/', function () {
    return view('index');
});


Route::get('/permintaan', [PublicRequestController::class, 'create'])->name('public-request.create');
Route::post('/permintaan', [PublicRequestController::class, 'store'])->name('public-request.store');

// Route::get('/antrian', [QueueController::class, 'index'])
//     ->name('public.queue');

Route::get('/antrian', [PublicRequestController::class, 'queue'])
    ->name('public.queue');

Route::post('/save-token', function (Illuminate\Http\Request $request) {

    $user = auth()->user();

    if (!$user) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    FcmToken::updateOrCreate(
        [
            'user_id' => $user->id,
            'token' => $request->token,
        ],
        []
    );

    return response()->json(['success' => true]);
})->middleware('auth');
