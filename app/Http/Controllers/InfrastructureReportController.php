<?php

namespace App\Http\Controllers;

use App\Models\Infrastructure;
use App\Models\Location;
use Barryvdh\DomPDF\Facade\Pdf;

class InfrastructureReportController extends Controller
{
    public function print(string $locationId = null)
    {
        $query = Infrastructure::with(['location', 'category']);

        if ($locationId && $locationId !== 'all') {
            $query->where('location_id', $locationId);
        }

        $infrastructures = $query->orderBy('location_id')->orderBy('category_id')->get();
        $location        = $locationId && $locationId !== 'all'
            ? Location::find($locationId)?->name
            : 'Semua Lokasi';

        $pdf = Pdf::loadView('pdf.infrastructure-report', compact('infrastructures', 'location'))
            ->setPaper('a4', 'landscape');

        return $pdf->stream('laporan-infrastruktur-' . now()->format('Ymd') . '.pdf');
    }
}
