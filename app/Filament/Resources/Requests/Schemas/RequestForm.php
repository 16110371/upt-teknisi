<?php

namespace App\Filament\Resources\Requests\Schemas;

use App\Models\Infrastructure;
use App\Models\InfrastructureUnit;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class RequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                DatePicker::make('request_date')
                    ->label('Tanggal')
                    ->required(),

                TextInput::make('requester_name')
                    ->label('Nama')
                    ->required(),

                Select::make('category_id')
                    ->label('Kategori')
                    ->relationship('category', 'name')
                    ->required()
                    ->live(),

                Select::make('location_id')
                    ->label('Lokasi')
                    ->relationship('location', 'name')
                    ->required()
                    ->live(),

                Select::make('infrastructure_id')
                    ->label('Item Infrastruktur')
                    ->nullable()
                    ->options(function ($get) {
                        $locationId = $get('location_id');
                        $categoryId = $get('category_id');

                        if (!$locationId || !$categoryId) return [];

                        return Infrastructure::where('location_id', $locationId)
                            ->where('category_id', $categoryId)
                            ->pluck('name', 'id');
                    })
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if (!$state) {
                            $set('broken_unit_ids', []);
                            $set('damaged_quantity', 1);
                            return;
                        }

                        // ✅ Kalau hanya 1 unit, auto pilih
                        $units = InfrastructureUnit::where('infrastructure_id', $state)
                            ->where('status', 'good')
                            ->where('is_active', true)
                            ->get();

                        if ($units->count() === 1) {
                            $set('broken_unit_ids', [$units->first()->id]);
                            $set('damaged_quantity', 1);
                        }
                    })
                    ->helperText('Pilih lokasi dan kategori terlebih dahulu'),

                // ✅ Pilih unit yang rusak
                Select::make('broken_unit_ids')
                    ->label('Unit yang Rusak')
                    ->multiple()
                    ->options(function ($get) {
                        $infraId = $get('infrastructure_id');
                        if (!$infraId) return [];

                        return InfrastructureUnit::where('infrastructure_id', $infraId)
                            ->where('status', 'good')
                            ->where('is_active', true)
                            ->pluck('code', 'id');
                    })
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set) {
                        // ✅ Auto isi jumlah rusak
                        $set('damaged_quantity', count($state ?? []));
                    })
                    ->hidden(fn($get) => !$get('infrastructure_id'))
                    ->required(fn($get) => (bool) $get('infrastructure_id'))
                    ->dehydrated(true),

                TextInput::make('damaged_quantity')
                    ->label('Jumlah Rusak')
                    ->numeric()
                    ->default(0)
                    ->readOnly() // ✅ otomatis dari pilih unit
                    ->hidden(fn($get) => !$get('infrastructure_id'))
                    ->dehydrated(true),

                // ✅ Pilih unit yang diperbaiki
                Select::make('fixed_unit_ids')
                    ->label('Unit yang Diperbaiki')
                    ->multiple()
                    ->options(function ($get) {
                        $infraId = $get('infrastructure_id');
                        if (!$infraId) return [];

                        return InfrastructureUnit::where('infrastructure_id', $infraId)
                            ->where('status', 'broken')
                            ->where('is_active', true)
                            ->pluck('code', 'id');
                    })
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set) {
                        $set('fixed_quantity', count($state ?? []));
                    })
                    ->hidden(fn($get) => !$get('infrastructure_id'))
                    ->dehydrated(true),

                TextInput::make('fixed_quantity')
                    ->label('Jumlah Diperbaiki')
                    ->numeric()
                    ->default(0)
                    ->readOnly()
                    ->hidden(fn($get) => !$get('infrastructure_id'))
                    ->dehydrated(true),

                // ✅ Pilih unit rusak permanen
                Select::make('permanent_unit_ids')
                    ->label('Unit Rusak Permanen')
                    ->multiple()
                    ->options(function ($get) {
                        $infraId = $get('infrastructure_id');
                        if (!$infraId) return [];

                        return InfrastructureUnit::where('infrastructure_id', $infraId)
                            ->where('status', 'broken')
                            ->where('is_active', true)
                            ->pluck('code', 'id');
                    })
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set) {
                        $set('permanent_quantity', count($state ?? []));
                    })
                    ->hidden(fn($get) => !$get('infrastructure_id'))
                    ->dehydrated(true),

                TextInput::make('permanent_quantity')
                    ->label('Jumlah Rusak Permanen')
                    ->numeric()
                    ->default(0)
                    ->readOnly()
                    ->hidden(fn($get) => !$get('infrastructure_id'))
                    ->dehydrated(true),

                Select::make('status')
                    ->label('Status')
                    ->options([
                        'Pending'          => 'Pending',
                        'Dikerjakan'       => 'Dikerjakan',
                        'Menunggu Part'    => 'Menunggu Part',
                        'Selesai'          => 'Selesai',
                        'Tidak Diperbaiki' => 'Tidak Diperbaiki',
                    ])
                    ->default('Pending')
                    ->required(),

                Select::make('priority')
                    ->label('Prioritas')
                    ->options([
                        'Rendah' => 'Rendah',
                        'Sedang' => 'Sedang',
                        'Tinggi' => 'Tinggi',
                    ])
                    ->default('Rendah')
                    ->required(),

                Select::make('technicians')
                    ->label('Teknisi')
                    ->relationship('technicians', 'name')
                    ->multiple()
                    ->preload()
                    ->nullable(),

                DateTimePicker::make('handled_at')
                    ->label('Waktu Ditangani')
                    ->nullable(),

                DateTimePicker::make('completed_at')
                    ->label('Waktu Selesai')
                    ->nullable(),

                FileUpload::make('photo')
                    ->image()
                    ->optimize('webp')
                    ->resize(50)
                    ->maxImageWidth(1200)
                    ->maxImageHeight(1200)
                    ->disk('public')
                    ->directory('requests')
                    ->label('Foto Kerusakan'),

                Textarea::make('description')
                    ->label('Deskripsi')
                    ->required()
                    ->columnSpanFull(),

                Textarea::make('technician_note')
                    ->label('Catatan Teknisi')
                    ->placeholder('Tuliskan hasil pekerjaan, kendala, atau catatan lainnya...')
                    ->rows(4)
                    ->nullable()
                    ->columnSpanFull(),
            ]);
    }
}
