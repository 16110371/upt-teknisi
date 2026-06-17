<?php

namespace App\Filament\Sarpras\Resources\GoodAllocations\Schemas;

use App\Models\Good;
use App\Models\GoodUnit;
use App\Models\Location;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class GoodAllocationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Detail Alokasi')
                    ->schema([
                        Select::make('good_id')
                            ->label('Barang')
                            ->options(function () {
                                return Good::where('stock', '>', 0)
                                    ->with(['goodsType', 'category'])
                                    ->get()
                                    ->mapWithKeys(fn($good) => [
                                        $good->id => "{$good->code} - {$good->name} (Stok: {$good->stock} {$good->unit})"
                                    ]);
                            })
                            ->searchable()
                            ->required()
                            ->live()
                            ->afterStateUpdated(fn(callable $set) => $set('quantity', 1)),

                        Select::make('location_id')
                            ->label('Lokasi/Ruang')
                            ->options(function () {
                                return Location::all()->mapWithKeys(fn($loc) => [
                                    $loc->id => $loc->name . ($loc->room_code ? " ({$loc->room_code})" : ' ⚠️ belum ada kode')
                                ]);
                            })
                            ->searchable()
                            ->required()
                            ->helperText('Pastikan lokasi sudah memiliki kode ruang'),

                        TextInput::make('quantity')
                            ->label('Jumlah')
                            ->numeric()
                            ->required()
                            ->minValue(1)
                            ->rules([
                                fn($get) => function ($attribute, $value, $fail) use ($get) {
                                    $good = Good::find($get('good_id'));
                                    if ($good && $value > $good->stock) {
                                        $fail("Jumlah melebihi stok tersedia ({$good->stock} {$good->unit})");
                                    }
                                }
                            ]),

                        DatePicker::make('allocation_date')
                            ->label('Tanggal Alokasi')
                            ->required()
                            ->default(now()),

                        // ✅ Toggle generate kode - tidak untuk barang habis pakai
                        Toggle::make('generate_unit_codes')
                            ->label('Generate Kode Inventaris per Unit')
                            ->default(true)
                            ->live()
                            ->helperText('Nonaktifkan untuk barang habis pakai (tinta, kertas, dll)')
                            ->dehydrated(false),

                        Textarea::make('note')
                            ->label('Catatan')
                            ->nullable()
                            ->rows(3)
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }
}
