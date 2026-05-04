<?php

namespace App\Filament\Resources\RoomChecks\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section as InfolistSection;
use Filament\Infolists\Components\RepeatableEntry;

class RoomChecksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->recordUrl(null)
            ->recordAction('view')
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

                TextColumn::make('catatan')
                    ->label('Catatan')
                    ->getStateUsing(function ($record) {
                        $notes = $record->items
                            ->where('status', 'Bermasalah')
                            ->pluck('note')
                            ->filter()
                            ->implode(', ');

                        return $notes ?: '-';
                    })
                    ->limit(50),

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
                ViewAction::make()
                    ->label('')
                    ->infolist([
                        InfolistSection::make('Informasi Pengecekan')
                            ->schema([
                                TextEntry::make('location.name')
                                    ->label('Lokasi'),
                                TextEntry::make('user.name')
                                    ->label('Diperiksa Oleh'),
                                TextEntry::make('created_at')
                                    ->label('Tanggal')
                                    ->dateTime('d M Y, H:i'),
                            ]),

                        InfolistSection::make('Detail Item')
                            ->schema([
                                RepeatableEntry::make('items')
                                    ->label('')
                                    ->schema([
                                        TextEntry::make('infrastructure.name')
                                            ->label('Item'),
                                        TextEntry::make('infrastructure.category.name')
                                            ->label('Kategori'),
                                        TextEntry::make('status')
                                            ->label('Status')
                                            ->badge()
                                            ->color(fn($state) => $state === 'Bermasalah' ? 'danger' : 'success'),
                                        TextEntry::make('quantity')
                                            ->label('Jumlah Bermasalah')
                                            ->default('-'),
                                        TextEntry::make('note')
                                            ->label('Keterangan')
                                            ->default('-')
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(2),
                            ]),
                    ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
