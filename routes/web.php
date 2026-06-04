<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicRequestController;
use App\Http\Controllers\QueueController;
use App\Models\FcmToken;
use App\Models\Infrastructure;
use App\Http\Controllers\InfrastructureReportController;
use App\Http\Controllers\UnitController;

Route::get('/', function () {
    return view('index');
});


Route::get('/permintaan', [PublicRequestController::class, 'create'])->name('public-request.create');
Route::post('/permintaan', [PublicRequestController::class, 'store'])
    ->name('public-request.store')
    ->middleware('throttle:5,1');

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

Route::get('/api/infrastructures', function (Illuminate\Http\Request $request) {
    return response()->json(
        \App\Models\Infrastructure::where('location_id', $request->location_id)
            ->where('category_id', $request->category_id)
            ->where('good', '>', 0)
            ->select('id', 'name', 'good', 'broken')
            ->get()
    );
});


// ✅ Cetak semua lokasi
Route::get('/admin/infrastructure-report', [InfrastructureReportController::class, 'print'])
    ->middleware('auth')
    ->name('infrastructure.report');

// ✅ Cetak per lokasi
Route::get('/admin/infrastructure-report/{locationId}', [InfrastructureReportController::class, 'print'])
    ->middleware('auth')
    ->name('infrastructure.report.location');


Route::get('/unit/{code}', [UnitController::class, 'show'])->name('unit.show');
Route::post('/unit/{code}/report', [UnitController::class, 'report'])->name('unit.report')
    ->middleware('throttle:5,1');

Route::get('/api/unit/{code}', function (string $code) {
    $unit = \App\Models\InfrastructureUnit::with([
        'infrastructure.location',
        'infrastructure.category',
        'logs' => fn($q) => $q->latest()->limit(5),
        'logs.request',
    ])->where('code', $code)->first();

    if (!$unit) {
        return response()->json(['error' => 'Unit tidak ditemukan']);
    }

    return response()->json([
        'id'       => $unit->id,
        'code'     => $unit->code,
        'status'   => $unit->status,
        'note'     => $unit->note,
        'location' => $unit->infrastructure->location->name,
        'category' => $unit->infrastructure->category->name,
        'name'     => $unit->infrastructure->name,
        'logs'     => $unit->logs->map(fn($log) => [
            'type'           => $log->type,
            'note'           => $log->note,
            'requester_name' => $log->request?->requester_name ?? '-',
            'created_at'     => $log->created_at->translatedFormat('d M Y, H:i'),
        ]),
    ]);
});

Route::get('/admin/unit/{id}/qr-pdf', [UnitController::class, 'printQr'])
    ->middleware('auth')
    ->name('unit.qr.pdf');

Route::get('/admin/infrastructure/{id}/qr-pdf-all', [UnitController::class, 'printAllQr'])
    ->middleware('auth')
    ->name('unit.qr.all.pdf');

Route::get('/api/units', function (\Illuminate\Http\Request $request) {
    return response()->json(
        \App\Models\InfrastructureUnit::where('infrastructure_id', $request->infrastructure_id)
            ->where('status', 'good')
            ->where('is_active', true)
            ->select('id', 'code', 'status')
            ->get()
    );
});
