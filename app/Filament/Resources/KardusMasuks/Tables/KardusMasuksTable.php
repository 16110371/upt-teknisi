<?php

namespace App\Filament\Resources\KardusMasuks\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class KardusMasuksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tanggal_masuk')
                    ->date()
                    ->sortable(),

                TextColumn::make('nama_kardus')
                    ->searchable(),

                ImageColumn::make('foto')
                    ->disk('public'),

                TextColumn::make('keterangan')
                    ->limit(40),
            ])
            ->defaultSort('tanggal_masuk', 'desc')
            ->filters([
                //
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
