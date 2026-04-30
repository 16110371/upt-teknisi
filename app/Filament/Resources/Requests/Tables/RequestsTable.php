<?php

namespace App\Filament\Resources\Requests\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\View;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;

class RequestsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function ($query) {
                $query->orderByRaw("FIELD(status, 'Pending', 'Dikerjakan', 'Menunggu Part', 'Selesai', 'Tidak Diperbaiki')")
                    ->orderBy('created_at', 'desc');
            })
            ->columns([
                TextColumn::make('requester_name')
                    ->label('Nama')
                    ->searchable(),
                TextColumn::make('request_date')
                    ->label('Dibuat')
                    ->date()
                    ->sortable(),
                TextColumn::make('category.name')
                    ->label('Kategori')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('location.name')
                    ->label('Lokasi')
                    ->numeric()
                    ->sortable(),

                // Tampilkan item infrastruktur
                // TextColumn::make('infrastructure.name')
                //     ->label('Item')
                //     ->default('-')
                //     ->sortable(),

                // Tampilkan jumlah rusak
                TextColumn::make('damaged_quantity')
                    ->label('Jml Rusak')
                    ->default('-')
                    ->sortable(),

                TextColumn::make('status')
                    ->color(fn(string $state): string => match ($state) {
                        'Pending'          => 'warning',
                        'Dikerjakan'       => 'info',
                        'Menunggu Part'    => 'gray',
                        'Selesai'          => 'success',
                        'Tidak Diperbaiki' => 'danger',
                        default            => 'gray',
                    })
                    ->badge(),
                TextColumn::make('priority')
                    ->color(fn(string $state): string => match ($state) {
                        'Rendah' => 'success',
                        'Sedang' => 'warning',
                        'Tinggi' => 'danger',
                        default => 'gray',
                    })
                    ->badge(),
                // TextColumn::make('technician.name')
                //     ->label('Teknisi')
                //     ->numeric()
                //     ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
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
                        'Rendah' => 'Rendah',
                        'Sedang' => 'Sedang',
                        'Tinggi' => 'Tinggi',
                    ]),

                SelectFilter::make('location_id')
                    ->label('Lokasi')
                    ->relationship('location', 'name'),
            ])
            ->recordActions([
                ViewAction::make()
                    ->label(''),
                EditAction::make()
                    ->label(''),
                DeleteAction::make()
                    ->label(''),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
