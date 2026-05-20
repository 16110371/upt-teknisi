<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\FcmToken;
use App\Models\InfrastructureUnit;
use App\Models\Request;
use App\Models\UnitLog;
use App\Services\FirebaseService;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Log;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;

class UnitController extends Controller
{
    public function show(string $code)
    {
        $unit = InfrastructureUnit::with([
            'infrastructure.location',
            'infrastructure.category',
            'logs' => fn($q) => $q->latest()->limit(10),
            'logs.request',
        ])->where('code', $code)->firstOrFail();

        return view('unit.show', compact('unit'));
    }

    public function report(HttpRequest $request, string $code)
    {
        $unit = InfrastructureUnit::with(['infrastructure.location', 'infrastructure.category'])
            ->where('code', $code)
            ->firstOrFail();

        $validated = $request->validate([
            'requester_name' => 'required|string|max:100',
            'description'    => 'required|string|max:2000',
            'priority'       => 'nullable|string|in:Rendah,Sedang,Tinggi',
        ]);

        $infra = $unit->infrastructure;

        // ✅ Buat request
        $requestModel = Request::create([
            'request_date'      => now(),
            'requester_name'    => $validated['requester_name'],
            'category_id'       => $infra->category_id,
            'location_id'       => $infra->location_id,
            'infrastructure_id' => $infra->id,
            'damaged_quantity'  => 1,
            'description'       => $validated['description'],
            'status'            => 'Pending',
            'priority'          => $validated['priority'] ?? 'Sedang',
        ]);

        // ✅ Update status unit
        $unit->update(['status' => 'broken']);

        // ✅ Simpan log unit
        UnitLog::create([
            'unit_id'    => $unit->id,
            'request_id' => $requestModel->id,
            'type'       => 'rusak',
            'note'       => $validated['description'],
        ]);

        // ✅ Kirim notifikasi FCM
        $tokens   = FcmToken::pluck('token');
        $firebase = app(FirebaseService::class);

        foreach ($tokens as $token) {
            try {
                $firebase->send(
                    $token,
                    'Laporan Kerusakan Unit',
                    "Unit {$unit->code} dilaporkan bermasalah oleh {$validated['requester_name']}",
                    url('/admin/requests')
                );
            } catch (\Exception $e) {
                Log::error('FCM error: ' . $e->getMessage());
            }
        }

        return redirect()->route('unit.show', $code)->with('success', true);
    }


    public function printQr(int $id)
    {
        $unit = InfrastructureUnit::with([
            'infrastructure.location',
            'infrastructure.category',
        ])->findOrFail($id);

        // ✅ Pakai SVG tapi di-encode untuk DomPDF
        $qrSvg = QrCode::size(200)
            ->format('svg')
            ->generate(url('/unit/' . $unit->code));

        // ✅ Convert SVG ke base64
        $qrBase64 = base64_encode($qrSvg);

        $pdf = Pdf::loadView('pdf.unit-qr', compact('unit', 'qrBase64'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream('qr-' . $unit->code . '.pdf');
    }

    public function printAllQr(int $id)
    {
        $units = InfrastructureUnit::with([
            'infrastructure.location',
            'infrastructure.category',
        ])
            ->where('infrastructure_id', $id)
            ->where('is_active', true)
            ->get();

        // ✅ Generate QR Code untuk semua unit
        $unitsWithQr = $units->map(function ($unit) {
            $qrSvg = QrCode::size(150)
                ->format('svg')
                ->generate(url('/unit/' . $unit->code));

            return [
                'unit'     => $unit,
                'qrBase64' => base64_encode($qrSvg),
            ];
        });

        $pdf = Pdf::loadView('pdf.units-qr-all', compact('unitsWithQr'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream('qr-all.pdf');
    }
}
