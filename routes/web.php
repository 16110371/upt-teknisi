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

    if (!$request->token) {
        return response()->json(['error' => 'Token kosong'], 422);
    }

    $platform = $request->platform ?? 'web';

    // ✅ Simpan token baru / update kalau sudah ada
    FcmToken::updateOrCreate(
        [
            'user_id' => $user->id,
            'token'   => $request->token,
        ],
        [
            'platform'   => $platform,
            'updated_at' => now(),
        ]
    );

    // ✅ Batasi maksimal 3 token per platform per user
    $tokens = FcmToken::where('user_id', $user->id)
        ->where('platform', $platform)
        ->orderBy('updated_at', 'desc')
        ->get();

    if ($tokens->count() > 3) {
        $tokens->slice(3)->each->delete();
    }

    return response()->json(['success' => true]);
})->middleware('auth');
