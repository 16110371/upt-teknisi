<?php

namespace App\Filament\Sarpras\Resources\GoodsTypes\Schemas;

use App\Models\GoodsCategory;
use App\Models\GoodsType;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class GoodsTypeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('goods_category_id')
                    ->label('Kategori')
                    ->options(GoodsCategory::pluck('name', 'id'))
                    ->searchable()
                    ->required()
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if (!$state) return;

                        // ✅ Auto generate kode melanjutkan yang sudah ada
                        $category = GoodsCategory::find($state);
                        if (!$category) return;

                        // Ambil semua kode dengan prefix kategori ini
                        $lastCode = GoodsType::where('goods_category_id', $state)
                            ->orderByRaw('CAST(SUBSTRING(code, 2) AS UNSIGNED) DESC')
                            ->value('code');

                        if ($lastCode) {
                            // Ambil angka dari kode terakhir dan tambah 1
                            $lastNumber = intval(substr($lastCode, strlen($category->code)));
                            $newNumber  = $lastNumber + 1;
                        } else {
                            $newNumber = 1;
                        }

                        $newCode = $category->code . $newNumber;
                        $set('code', $newCode);
                    }),

                TextInput::make('code')
                    ->label('Kode Jenis')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(10)
                    ->helperText('Otomatis terisi, bisa diubah manual jika perlu'),

                TextInput::make('name')
                    ->label('Nama Jenis Barang')
                    ->required()
                    ->maxLength(100)
                    ->placeholder('Contoh: LAPTOP, PROYEKTOR'),
            ]);
    }
}
