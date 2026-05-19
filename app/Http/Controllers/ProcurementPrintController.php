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

        // Ambil semua barang yang dicentang, urutkan berdasarkan tanggal terkecil
        $records = Procurement::with('location')
            ->whereIn('id', $idArray)
            ->orderBy('requested_at', 'asc')
            ->get();

        if ($records->isEmpty()) {
            abort(404, 'Data tidak ditemukan');
        }

        // Ambil data urutan pertama untuk nama pemohon & jabatan di tanda tangan
        $firstRecord = $records->first();

        // Tetap gunakan kertas Portrait (A4) karena formatnya tetap format 1 item
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.procurement-bulk-single-format', compact('records', 'firstRecord'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream('Pengajuan-Sarpras.pdf');
    }
}
