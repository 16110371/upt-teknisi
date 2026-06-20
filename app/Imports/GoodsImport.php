<?php

namespace App\Imports;

use App\Models\Good;
use App\Models\GoodsCategory;
use App\Models\GoodsType;
use App\Models\Supplier;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class GoodsImport implements ToModel, WithHeadingRow, SkipsEmptyRows
{
    public function model(array $row)
    {
        // ✅ Cari kategori berdasarkan kode
        $category = GoodsCategory::where('code', strtoupper(trim($row['kategori'] ?? '')))
            ->first();

        if (!$category) return null;

        // ✅ Cari jenis barang berdasarkan kode
        $goodsType = GoodsType::where('code', strtoupper(trim($row['kode_jenis'] ?? '')))
            ->first();

        // ✅ Cari/buat supplier
        $supplier = null;
        if (!empty($row['supplier'])) {
            $supplier = Supplier::firstOrCreate(
                ['name' => trim($row['supplier'])],
            );
        }

        $quantity = intval($row['jumlah'] ?? 0);
        if ($quantity <= 0) return null;

        return new Good([
            'code'              => strtoupper(trim($row['kode_jenis'] ?? '')),
            'goods_category_id' => $category->id,
            'goods_type_id'     => $goodsType?->id,
            'supplier_id'       => $supplier?->id,
            'name'              => trim($row['nama_barang'] ?? ''),
            'brand'             => trim($row['merk'] ?? '') ?: null,
            'specification'     => trim($row['spesifikasi'] ?? '') ?: null,
            'unit'              => trim($row['satuan'] ?? 'unit'),
            'quantity'          => $quantity,
            'stock'             => $quantity,
            'price'             => !empty($row['harga'])
                ? floatval(str_replace(['.', ','], ['', '.'], $row['harga']))
                : null,
            'purchase_date'     => !empty($row['tanggal_beli'])
                ? \Carbon\Carbon::parse($row['tanggal_beli'])->format('Y-m-d')
                : null,
            'procurement_year'  => !empty($row['tahun'])
                ? intval($row['tahun'])
                : now()->year,
            'is_consumable'     => in_array(
                strtolower(trim($row['habis_pakai'] ?? '')),
                ['ya', 'yes', '1', 'true']
            ),
            'funding_source'    => $this->parseFundingSource($row['sumber_dana'] ?? ''),
            'note'              => trim($row['catatan'] ?? '') ?: null,
        ]);
    }

    private function parseFundingSource(string $value): ?string
    {
        $value = strtoupper(trim($value));
        return match (true) {
            str_contains($value, 'BOSDA')   => 'BOSDA',
            str_contains($value, 'BOS')     => 'BOS',
            str_contains($value, 'SEKOLAH') => 'Sekolah',
            str_contains($value, 'BANTUAN') => 'Bantuan',
            default                         => null,
        };
    }
}
