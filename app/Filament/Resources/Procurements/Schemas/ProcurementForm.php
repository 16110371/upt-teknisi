<?php

namespace App\Filament\Resources\Procurements\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;

class ProcurementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('item_name')
                    ->label('Nama Barang')
                    ->required()
                    ->placeholder('Contoh: Laptop Core i5')
                    ->maxLength(255),
                TextInput::make('purchase_url')
                    ->label('Link Pembelian')
                    ->url()
                    ->placeholder('https://...')
                    ->suffixIcon('heroicon-m-link'),
                TextInput::make('quantity')
                    ->label('Jumlah')
                    ->numeric()
                    ->default(1)
                    ->required()
                    ->minValue(1),
                TextInput::make('estimated_price')
                    ->label('Harga Satuan')
                    ->numeric()
                    ->prefix('Rp')
                    ->step(1)
                    ->required(),
                DatePicker::make('requested_at')
                    ->label('Tanggal Pengajuan')
                    ->default(now())
                    ->required(),
                Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Disetujui',
                        'purchased' => 'Sudah Dibeli',
                    ])
                    ->default('pending')
                    ->live()
                    ->required(),
                FileUpload::make('signed_document_photo')
                    ->label('Foto Dokumen Tanda Tangan')
                    ->optimize('webp')
                    ->resize(50)
                    ->maxImageWidth(1200)
                    ->maxImageHeight(1200)
                    ->image()
                    ->directory('procurement-signatures')
                    ->visible(fn($get) => $get('status') === 'approved')
                    ->required(fn($get) => $get('status') === 'approved'),
                TextInput::make('requested_by')
                    ->label('Nama Pemohon / Penanggung Jawab')
                    ->required(),
                TextInput::make('position')
                    ->label('Jabatan')
                    ->placeholder('Contoh: Kepala Lab / Staff Sarpras')
                    ->required(),
                Select::make('location_id')
                    ->label('Lokasi')
                    ->relationship('location', 'name')
                    ->required()
                    ->live(),
                Textarea::make('description')
                    ->label('Spesifikasi / Catatan')
                    ->placeholder('Contoh: RAM 16GB, SSD 512GB')
                    ->columnSpanFull(),
            ]);
    }
}
