<?php

namespace App\Filament\Sarpras\Resources\Goods\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class GoodsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('code')
                    ->label('Kode')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('gray'),

                TextColumn::make('name')
                    ->label('Nama Barang')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                TextColumn::make('category.name')
                    ->label('Kategori')
                    ->sortable(),

                TextColumn::make('brand')
                    ->label('Merk')
                    ->default('-')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('specification')
                    ->label('Spesifikasi')
                    ->default('-')
                    ->limit(30)
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('unit')
                    ->label('Satuan'),

                TextColumn::make('quantity')
                    ->label('Total')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('funding_source')
                    ->label('Sumber Dana')
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        'BOS'     => 'info',
                        'BOSDA'   => 'success',
                        'Sekolah' => 'danger',
                        'Bantuan' => 'warning',
                        default   => 'gray',
                    })
                    ->default('-'),

                TextColumn::make('stock')
                    ->label('Stok')
                    ->numeric()
                    ->sortable()
                    ->color(fn($state) => $state > 0 ? 'success' : 'danger'),

                TextColumn::make('price')
                    ->label('Harga')
                    ->money('IDR')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('purchase_date')
                    ->label('Tgl Beli')
                    ->date('d M Y')
                    ->default('-')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                IconColumn::make('is_consumable')
                    ->label('Habis Pakai')
                    ->boolean(),

                TextColumn::make('supplier.name')
                    ->label('Supplier')
                    ->default('-')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('goods_category_id')
                    ->label('Kategori')
                    ->relationship('category', 'name'),

                SelectFilter::make('goods_type_id')
                    ->label('Jenis Barang')
                    ->relationship('goodsType', 'name')
                    ->searchable(),

                TernaryFilter::make('is_consumable')
                    ->label('Habis Pakai'),

                SelectFilter::make('supplier_id')
                    ->label('Supplier')
                    ->relationship('supplier', 'name'),
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
