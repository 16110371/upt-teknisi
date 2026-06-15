<?php

namespace App\Filament\Sarpras\Resources\ProcurementRequests\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class ProcurementRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Pengajuan')
                    ->schema([
                        TextInput::make('title')
                            ->label('Judul Pengajuan')
                            ->required()
                            ->maxLength(200)
                            ->placeholder('Contoh: Pengadaan Komputer Lab TKJ'),

                        Textarea::make('reason')
                            ->label('Alasan Pengajuan')
                            ->required()
                            ->rows(4)
                            ->placeholder('Jelaskan alasan pengajuan barang ini...')
                            ->columnSpanFull(),
                    ]),

                Section::make('Daftar Barang yang Diajukan')
                    ->description('Tambahkan barang yang ingin diajukan')
                    ->schema([
                        Repeater::make('items')
                            ->label('')
                            ->relationship('items')
                            ->schema([
                                TextInput::make('name')
                                    ->label('Nama Barang')
                                    ->required()
                                    ->maxLength(200),

                                TextInput::make('specification')
                                    ->label('Spesifikasi')
                                    ->nullable()
                                    ->maxLength(500)
                                    ->columnSpanFull(),

                                TextInput::make('quantity')
                                    ->label('Jumlah')
                                    ->numeric()
                                    ->required()
                                    ->minValue(1)
                                    ->default(1),

                                TextInput::make('unit')
                                    ->label('Satuan')
                                    ->required()
                                    ->placeholder('pcs, unit, set, rim, dll'),

                                TextInput::make('estimated_price')
                                    ->label('Estimasi Harga Satuan')
                                    ->numeric()
                                    ->nullable()
                                    ->prefix('Rp'),

                                Textarea::make('note')
                                    ->label('Catatan')
                                    ->nullable()
                                    ->rows(2)
                                    ->columnSpanFull(),
                            ])
                            ->columns(2)
                            ->addActionLabel('Tambah Barang')
                            ->minItems(1),
                    ]),
            ]);
    }
}
