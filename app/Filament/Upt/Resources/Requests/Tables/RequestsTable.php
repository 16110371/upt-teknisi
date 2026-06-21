<?php

namespace App\Filament\Upt\Resources\Requests\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class RequestsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('request_date', 'desc')
            ->columns([
                TextColumn::make('request_date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('requester_name')
                    ->label('Pelapor')
                    ->searchable(),

                TextColumn::make('location.name')
                    ->label('Lokasi')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('description')
                    ->label('Deskripsi')
                    ->limit(40)
                    ->searchable(),

                TextColumn::make('priority')
                    ->label('Prioritas')
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        'Tinggi' => 'danger',
                        'Sedang' => 'warning',
                        'Rendah' => 'success',
                        default  => 'gray',
                    }),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        'Pending'          => 'warning',
                        'Dikerjakan'       => 'info',
                        'Menunggu Part'    => 'gray',
                        'Selesai'          => 'success',
                        'Tidak Diperbaiki' => 'danger',
                        default            => 'gray',
                    }),

                TextColumn::make('technicians.name')
                    ->label('Teknisi')
                    ->badge()
                    ->separator(',')
                    ->default('-'),

                TextColumn::make('handled_at')
                    ->label('Ditangani')
                    ->dateTime('d M Y')
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('completed_at')
                    ->label('Selesai')
                    ->dateTime('d M Y')
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'Pending'          => 'Pending',
                        'Dikerjakan'       => 'Dikerjakan',
                        'Menunggu Part'    => 'Menunggu Part',
                        'Selesai'          => 'Selesai',
                        'Tidak Diperbaiki' => 'Tidak Diperbaiki',
                    ]),

                SelectFilter::make('priority')
                    ->label('Prioritas')
                    ->options([
                        'Tinggi' => 'Tinggi',
                        'Sedang' => 'Sedang',
                        'Rendah' => 'Rendah',
                    ]),

                SelectFilter::make('location_id')
                    ->label('Lokasi')
                    ->relationship('location', 'name'),
            ])
            ->recordActions([
                ViewAction::make()->label(''),
                EditAction::make()->label(''),
            ])
            ->recordUrl(null)
            ->recordAction('view')
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
