<?php

namespace App\Filament\Sarpras\Resources\Locations\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LocationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Lokasi')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('room_code')
                    ->label('Kode Ruang')
                    ->badge()
                    ->color(fn($state) => $state ? 'success' : 'gray')
                    ->default('Belum diatur'),
            ])
            ->filters([])
            ->recordActions([
                EditAction::make()->label('Atur Kode'),
            ])
            ->toolbarActions([]);
    }
}
