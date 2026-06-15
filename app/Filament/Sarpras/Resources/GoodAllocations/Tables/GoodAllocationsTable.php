<?php

namespace App\Filament\Sarpras\Resources\GoodAllocations\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class GoodAllocationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('good.code')
                    ->label('Kode Barang')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('gray'),

                TextColumn::make('good.name')
                    ->label('Nama Barang')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('location.name')
                    ->label('Lokasi')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('quantity')
                    ->label('Jumlah')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('good.unit')
                    ->label('Satuan'),

                TextColumn::make('user.name')
                    ->label('Dialokasikan Oleh')
                    ->sortable(),

                TextColumn::make('allocation_date')
                    ->label('Tanggal Alokasi')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('note')
                    ->label('Catatan')
                    ->limit(40)
                    ->default('-')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('location_id')
                    ->label('Lokasi')
                    ->relationship('location', 'name'),

                SelectFilter::make('good_id')
                    ->label('Barang')
                    ->relationship('good', 'name'),
            ])
            ->recordActions([
                EditAction::make()->label(''),
                DeleteAction::make()->label(''),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
