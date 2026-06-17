<?php

namespace App\Filament\Sarpras\Resources\Goods\Schemas;

use App\Models\GoodsCategory;
use App\Models\GoodsType;
use App\Models\ProcurementItem;
use App\Models\Supplier;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Hidden;
use Filament\Schemas\Components\Grid;

class GoodForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Barang')
                    ->schema([
                        Select::make('goods_category_id')
                            ->label('Kategori')
                            ->options(GoodsCategory::pluck('name', 'id'))
                            ->searchable()
                            ->required()
                            ->live()
                            ->disabled(fn($get) => (bool) request('type_id'))
                            ->dehydrated(true)
                            ->afterStateUpdated(fn(callable $set) => $set('goods_type_id', null)),

                        Select::make('goods_type_id')
                            ->label('Jenis Barang')
                            ->options(function ($get) {
                                $categoryId = $get('goods_category_id');
                                if (!$categoryId) return [];

                                return GoodsType::where('goods_category_id', $categoryId)
                                    ->orderBy('code')
                                    ->get()
                                    ->mapWithKeys(fn($type) => [
                                        $type->id => "{$type->code} - {$type->name}"
                                    ]);
                            })
                            ->searchable()
                            ->required()
                            ->live()
                            ->disabled(fn($get) => (bool) request('type_id'))
                            ->dehydrated(true)
                            ->afterStateUpdated(function ($state, callable $set) {
                                $type = GoodsType::find($state);
                                if ($type) {
                                    $set('code', $type->code);
                                    $set('name', $type->name);
                                }
                            })
                            ->disabled(fn($get) => !$get('goods_category_id'))
                            ->helperText('Pilih kategori terlebih dahulu'),

                        TextInput::make('code')
                            ->label('Kode Jenis Barang (Global)')
                            ->required()
                            ->readOnly()
                            ->dehydrated(true)
                            ->helperText('Otomatis terisi dari jenis barang yang dipilih'),

                        TextInput::make('name')
                            ->label('Nama Barang')
                            ->required()
                            ->maxLength(200)
                            ->helperText('Bisa disesuaikan, contoh: Monitor LG 22 Inch'),

                        TextInput::make('brand')
                            ->label('Merk')
                            ->nullable()
                            ->maxLength(100),

                        TextInput::make('specification')
                            ->label('Spesifikasi')
                            ->nullable()
                            ->maxLength(500),

                        Toggle::make('is_consumable')
                            ->label('Barang Habis Pakai')
                            ->default(false)
                            ->helperText('Centang jika barang ini habis pakai (tinta, kertas, dll)'),

                        Select::make('funding_source')
                            ->label('Sumber Dana')
                            ->options([
                                'BOS'     => '🔵 Dana BOS',
                                'BOSDA'   => '🟢 Dana BOSDA',
                                'Sekolah' => '🔴 Dana Sekolah',
                                'Bantuan' => '🟡 Dana Bantuan',
                            ])
                            ->nullable()
                            ->native(false),

                        FileUpload::make('photo')
                            ->label('Foto Barang')
                            ->image()
                            ->disk('public')
                            ->directory('goods')
                            // ->imageEditor()
                            ->nullable()
                            ->columnSpanFull(),
                    ])->columns(2),


                // ✅ Wrap Section Detail Pengadaan & Stok dalam Grid 2 kolom
                Grid::make(2)
                    ->schema([
                        Section::make('Detail Pengadaan')
                            ->schema([
                                Select::make('procurement_item_id')
                                    ->label('Dari Pengajuan')
                                    ->options(ProcurementItem::with('procurementRequest')
                                        ->get()
                                        ->mapWithKeys(fn($item) => [
                                            $item->id => $item->name . ' (' . $item->procurementRequest->title . ')'
                                        ]))
                                    ->searchable()
                                    ->nullable(),

                                Select::make('supplier_id')
                                    ->label('Supplier')
                                    ->options(Supplier::pluck('name', 'id'))
                                    ->searchable()
                                    ->nullable(),

                                DatePicker::make('purchase_date')
                                    ->label('Tanggal Pembelian')
                                    ->nullable(),

                                TextInput::make('price')
                                    ->label('Harga Satuan')
                                    ->numeric()
                                    ->nullable()
                                    ->prefix('Rp'),

                                TextInput::make('procurement_year')
                                    ->label('Tahun Pengadaan')
                                    ->numeric()
                                    ->required()
                                    ->default(now()->year)
                                    ->minValue(2000)
                                    ->maxValue(now()->year)
                                    ->helperText('Digunakan untuk format kode inventaris'),
                            ]),

                        Section::make('Stok')
                            ->schema([
                                Select::make('unit')
                                    ->label('Satuan')
                                    ->options([
                                        'pcs'     => 'Pcs',
                                        'unit'    => 'Unit',
                                        'set'     => 'Set',
                                        'box'     => 'Box',
                                        'dus'     => 'Dus',
                                        'rim'     => 'Rim',
                                        'lusin'   => 'Lusin',
                                        'pack'    => 'Pack',
                                        'roll'    => 'Roll',
                                        'botol'   => 'Botol',
                                        'buah'    => 'Buah',
                                        'meter'   => 'Meter',
                                        'lembar'  => 'Lembar',
                                        'lainnya' => '✏️ Lainnya...',
                                    ])
                                    ->required()
                                    ->live()
                                    ->native(false),

                                TextInput::make('unit_custom')
                                    ->label('Tulis Satuan Lain')
                                    ->visible(fn($get) => $get('unit') === 'lainnya')
                                    ->required(fn($get) => $get('unit') === 'lainnya')
                                    ->dehydrated(false),

                                TextInput::make('quantity')
                                    ->label('Jumlah Total')
                                    ->numeric()
                                    ->required()
                                    ->minValue(1)
                                    ->live()
                                    ->afterStateUpdated(fn($state, callable $set) => $set('stock', $state)),

                                TextInput::make('stock')
                                    ->label('Stok Tersedia')
                                    ->numeric()
                                    ->required()
                                    ->minValue(0)
                                    ->helperText('Jumlah yang belum dialokasikan'),
                            ]),
                    ]),
                Section::make('Catatan')
                    ->schema([
                        Textarea::make('note')
                            ->label('Catatan')
                            ->nullable()
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
