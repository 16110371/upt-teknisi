<?php

namespace App\Filament\Sarpras\Resources\Locations\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class LocationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Pengaturan Lokasi')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Lokasi')
                            ->required()
                            ->maxLength(100)
                            ->placeholder('Contoh: Lab TJKT 1, Lab DKV 2'),

                        TextInput::make('room_code')
                            ->label('Kode Ruang')
                            ->placeholder('Contoh: TJKT1, DKV1, GURU')
                            ->maxLength(20)
                            ->helperText('Dipakai dalam format kode inventaris, contoh: A10-TJKT1-26-001')
                            ->nullable(),
                    ])->columns(2),
            ]);
    }
}
