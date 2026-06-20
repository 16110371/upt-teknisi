<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicRequestController;
use App\Http\Controllers\QueueController;
use App\Models\FcmToken;
use App\Models\Infrastructure;
use App\Http\Controllers\InfrastructureReportController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\SarprasController;
use App\Filament\Sarpras\Resources\Goods\Pages\GoodsByType;
use App\Filament\Sarpras\Resources\Goods\Pages\GoodDetail;
use App\Filament\Sarpras\Resources\Goods\Pages\CreateGood;
use App\Filament\Sarpras\Resources\Goods\Pages\EditGood;
use App\Filament\Sarpras\Pages\InventarisPage;
use App\Filament\Sarpras\Pages\UnitDetailPage;


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


Route::middleware('auth')->group(function () {
    Route::get('/sarpras/good-allocations/{id}/print-qr', [SarprasController::class, 'printQr'])
        ->name('sarpras.allocation.qr');

    Route::get('/sarpras/good-units/{id}/print-qr', [SarprasController::class, 'printUnitQr'])
        ->name('sarpras.unit.qr');

    Route::get('/sarpras/good-units/print-qr-bulk', [SarprasController::class, 'printBulkQr'])
        ->name('sarpras.units.qr.bulk');
});

Route::middleware([
    'web',
    'auth',
    \Filament\Http\Middleware\SetUpPanel::class . ':sarpras',
])->group(function () {
    Route::get('/sarpras/goods/by-type/{typeId}', GoodsByType::class)
        ->name('sarpras.goods.by-type');

    Route::get('/sarpras/goods/detail/{goodId}', GoodDetail::class)
        ->name('sarpras.goods.detail');

    Route::get('/sarpras/goods/create', CreateGood::class)
        ->name('sarpras.goods.create');

    Route::get('/sarpras/goods/{id}/edit', EditGood::class)
        ->name('sarpras.goods.edit');

    // ✅ Inventaris per lokasi
    Route::get('/sarpras/inventaris', InventarisPage::class)
        ->name('sarpras.inventaris');

    // ✅ Detail per unit (Layer 2) - nanti kita buat
    Route::get('/sarpras/inventaris/unit/{id}', UnitDetailPage::class)
        ->name('sarpras.inventaris.unit');
});

// ✅ Download template import barang
Route::get('/sarpras/goods/template', function () {
    return \Maatwebsite\Excel\Facades\Excel::download(
        new class implements
            \Maatwebsite\Excel\Concerns\FromArray,
            \Maatwebsite\Excel\Concerns\WithHeadings {
            public function array(): array
            {
                return [[
                    'A',
                    'A10',
                    'Monitor LG 22"',
                    '22 inch Full HD',
                    'LG',
                    24,
                    'unit',
                    1500000,
                    '2022-01-15',
                    2022,
                    'Toko ABC',
                    'tidak',
                    'BOS',
                    '-'
                ]];
            }
            public function headings(): array
            {
                return [
                    'kategori',
                    'kode_jenis',
                    'nama_barang',
                    'spesifikasi',
                    'merk',
                    'jumlah',
                    'satuan',
                    'harga',
                    'tanggal_beli',
                    'tahun',
                    'supplier',
                    'habis_pakai',
                    'sumber_dana',
                    'catatan'
                ];
            }
        },
        'template-import-barang.xlsx'
    );
})->name('sarpras.goods.template')->middleware('auth');
