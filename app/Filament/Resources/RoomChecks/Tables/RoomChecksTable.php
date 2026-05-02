<?php

namespace App\Filament\Resources\RoomChecks\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class RoomChecksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('location.name')
                    ->label('Lokasi')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('user.name')
                    ->label('Diperiksa Oleh')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('items_count')
                    ->label('Total Item')
                    ->counts('items')
                    ->sortable(),

                TextColumn::make('bermasalah_count')
                    ->label('Bermasalah')
                    ->getStateUsing(fn($record) => $record->items->where('status', 'Bermasalah')->sum('quantity'))
                    ->color(fn($state) => $state > 0 ? 'danger' : 'success')
                    ->badge(),

                TextColumn::make('note')
                    ->label('Catatan')
                    ->limit(50)
                    ->default('-'),

                TextColumn::make('created_at')
                    ->label('Tanggal Pengecekan')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('location_id')
                    ->label('Lokasi')
                    ->relationship('location', 'name'),
            ])
            ->recordActions([
                ViewAction::make()->label(''),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
