<?php

namespace App\Filament\Sarpras\Resources\GoodUnits\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Actions\BulkAction;
use Illuminate\Database\Eloquent\Collection;

class GoodUnitsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('code', 'asc')
            ->columns([
                TextColumn::make('code')
                    ->label('Kode Inventaris')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('success'),

                TextColumn::make('good.name')
                    ->label('Nama Barang')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('good.code')
                    ->label('Kode Jenis')
                    ->badge()
                    ->color('gray'),

                TextColumn::make('location.name')
                    ->label('Lokasi')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('good.brand')
                    ->label('Merk')
                    ->default('-')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('good.specification')
                    ->label('Spesifikasi')
                    ->default('-')
                    ->limit(30)
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
                    ->relationship('good', 'name')
                    ->searchable(),
            ])
            ->recordActions([
                // ✅ Cetak QR per unit
                Action::make('print_qr')
                    ->label('QR')
                    ->icon('heroicon-o-qr-code')
                    ->color('success')
                    ->url(fn($record) => route('sarpras.unit.qr', $record->id))
                    ->openUrlInNewTab(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    // ✅ Cetak QR yang dipilih
                    BulkAction::make('print_selected_qr')
                        ->label('Cetak QR Terpilih')
                        ->icon('heroicon-o-printer')
                        ->color('success')
                        ->action(function (Collection $records) {
                            $ids = $records->pluck('id')->implode(',');
                            return redirect()->route('sarpras.units.qr.bulk', ['ids' => $ids]);
                        }),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
