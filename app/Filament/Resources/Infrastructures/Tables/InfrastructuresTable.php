<?php

namespace App\Filament\Resources\Infrastructures\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Actions\Action;
use App\Models\Location;

class InfrastructuresTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('location.name')
                    ->label('Lokasi')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('category.name')
                    ->label('Kategori')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('name')
                    ->label('Nama Item')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('total')
                    ->label('Total')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('good')
                    ->label('Baik')
                    ->numeric()
                    ->sortable()
                    ->color('success'),

                TextColumn::make('broken')
                    ->label('Rusak')
                    ->numeric()
                    ->sortable()
                    ->color(fn($record) => $record->broken > 0 ? 'danger' : 'success'),

                TextColumn::make('permanent_broken')
                    ->label('Rusak Permanen')
                    ->numeric()
                    ->sortable()
                    ->color(fn($record) => $record->permanent_broken > 0 ? 'danger' : 'success'),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('location_id')
                    ->label('Lokasi')
                    ->relationship('location', 'name'),

                SelectFilter::make('category_id')
                    ->label('Kategori')
                    ->relationship('category', 'name'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                // ✅ Tombol cetak semua lokasi
                Action::make('cetak_semua')
                    ->label('Cetak Semua')
                    ->icon('heroicon-o-printer')
                    ->color('success')
                    ->url(route('infrastructure.report'))
                    ->openUrlInNewTab(),
                // ✅ Tombol cetak per lokasi
                Action::make('cetak_per_lokasi')
                    ->label('Cetak Per Lokasi')
                    ->icon('heroicon-o-printer')
                    ->color('info')
                    ->form([
                        \Filament\Forms\Components\Select::make('location_id')
                            ->label('Pilih Lokasi')
                            ->options(Location::pluck('name', 'id'))
                            ->required(),
                    ])
                    ->action(function (array $data, \Livewire\Component $livewire) {
                        $url = route('infrastructure.report.location', $data['location_id']);
                        $livewire->js("window.open('{$url}', '_blank')");
                    }),
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
