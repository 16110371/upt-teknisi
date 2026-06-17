<?php

namespace App\Http\Controllers;

use App\Models\GoodAllocation;
use App\Models\GoodUnit;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class SarprasController extends Controller
{
    public function printQr(int $id)
    {
        $allocation = GoodAllocation::with([
            'good.category',
            'good.goodsType',
            'location',
            'goodUnits',
        ])->findOrFail($id);

        // ✅ Generate QR untuk setiap unit
        $units = $allocation->goodUnits->map(function ($unit) {
            $qrSvg = QrCode::size(150)
                ->format('svg')
                ->generate($unit->code);

            return [
                'unit'     => $unit,
                'qrBase64' => base64_encode($qrSvg),
            ];
        });

        $pdf = Pdf::loadView('pdf.good-units-qr', compact('allocation', 'units'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream('qr-' . $allocation->id . '.pdf');
    }

    public function printUnitQr(int $id)
    {
        $unit = \App\Models\GoodUnit::with([
            'good.category',
            'good.goodsType',
            'location',
        ])->findOrFail($id);

        $qrSvg = QrCode::size(200)
            ->format('svg')
            ->generate($unit->code);

        $qrBase64 = base64_encode($qrSvg);

        $pdf = Pdf::loadView('pdf.good-unit-single-qr', compact('unit', 'qrBase64'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream('qr-' . $unit->code . '.pdf');
    }

    public function printBulkQr(\Illuminate\Http\Request $request)
    {
        $ids   = explode(',', $request->ids);
        $units = \App\Models\GoodUnit::with([
            'good.goodsType',
            'location',
        ])->whereIn('id', $ids)->orderBy('code')->get();

        $unitsWithQr = $units->map(function ($unit) {
            $qrSvg = QrCode::size(150)
                ->format('svg')
                ->generate($unit->code);

            return [
                'unit'     => $unit,
                'qrBase64' => base64_encode($qrSvg),
            ];
        });

        $pdf = Pdf::loadView('pdf.good-units-bulk-qr', compact('unitsWithQr'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream('qr-bulk.pdf');
    }
}
