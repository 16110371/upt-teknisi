<?php

namespace App\Filament\Resources\Requests\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use App\Models\Infrastructure;

class RequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                DatePicker::make('request_date')
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

                        if (!$locationId || !$categoryId) {
                            return [];
                        }

                        return Infrastructure::where('location_id', $locationId)
                            ->where('category_id', $categoryId)
                            ->pluck('name', 'id');
                    })
                    ->live()
                    ->helperText('Pilih lokasi dan kategori terlebih dahulu'),
                TextInput::make('damaged_quantity')
                    ->label('Jumlah Rusak')
                    ->numeric()
                    ->default(1)
                    ->minValue(1)
                    ->hidden(fn($get) => !$get('infrastructure_id')),
                Select::make('status')
                    ->label('Status')
                    ->options([
                        'Pending'           => 'Pending',
                        'Dikerjakan'        => 'Dikerjakan',
                        'Menunggu Part'     => 'Menunggu Part',
                        'Selesai'           => 'Selesai',
                        'Tidak Diperbaiki'  => 'Tidak Diperbaiki',
                    ])
                    ->default('Pending')
                    ->required(),
                Select::make('technician_id')
                    ->label('Teknisi')
                    ->relationship('technician', 'name'),
                DateTimePicker::make('handled_at'),
                DateTimePicker::make('completed_at'),
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
                    ->required(),
                Textarea::make('technician_note')
                    ->label('Catatan Teknisi')
                    ->placeholder('Tuliskan hasil pekerjaan, kendala, atau catatan lainnya...')
                    ->rows(4)
                    ->columnSpanFull(),
            ]);
    }
}
