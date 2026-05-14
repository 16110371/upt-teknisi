<?php

namespace App\Http\Controllers;

use App\Models\Procurement;
use Barryvdh\DomPDF\Facade\Pdf;

class ProcurementPrintController extends Controller
{
    public function printSingle(Procurement $record)
    {
        // Ambil view dan masukkan data
        $pdf = Pdf::loadView('pdf.procurement-pdf', compact('record'))
            ->setPaper('a4', 'portrait');

        // Download otomatis atau stream (tampil di browser)
        return $pdf->stream('Laporan-Pengadaan-' . $record->id . '.pdf');
    }

    public function printBulk($ids)
    {
        $idArray = explode(',', $ids);
        // Mengambil data beserta relasi lokasinya
        $records = Procurement::with('location')->whereIn('id', $idArray)->get();

        $pdf = Pdf::loadView('pdf.procurement-bulk-pdf', compact('records'))
            ->setPaper('a4', 'landscape'); // Landscape agar tabel muat banyak kolom

        return $pdf->stream('Laporan-Kolektif-Sarpras.pdf');
    }
}
