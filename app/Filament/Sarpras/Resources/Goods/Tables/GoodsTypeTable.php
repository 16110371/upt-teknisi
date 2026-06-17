<?php

namespace App\Filament\Sarpras\Resources\Goods\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Actions\Action;

class GoodsTypeTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('code', 'asc')
            ->columns([
                TextColumn::make('code')
                    ->label('Kode')
                    ->badge()
                    ->color('gray')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('name')
                    ->label('Jenis Barang')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('category.name')
                    ->label('Kategori')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                TextColumn::make('goods_count')
                    ->label('Jumlah Item')
                    ->counts('goods')
                    ->sortable(),

                TextColumn::make('goods_sum_quantity')
                    ->label('Total Unit')
                    ->sum('goods', 'quantity')
                    ->sortable(),

                TextColumn::make('goods_sum_stock')
                    ->label('Stok Tersedia')
                    ->sum('goods', 'stock')
                    ->color(fn($state) => $state > 0 ? 'success' : 'danger')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('goods_category_id')
                    ->label('Kategori')
                    ->relationship('category', 'name'),
            ])
            ->recordActions([
                // ✅ Klik → masuk ke Level 2
                Action::make('lihat')
                    ->label('Lihat Barang')
                    ->icon('heroicon-o-eye')
                    ->color('primary')
                    ->url(fn($record) => route('sarpras.goods.by-type', $record->id)),
            ])
            ->recordUrl(fn($record) => route('sarpras.goods.by-type', $record->id));
    }
}
