<?php

namespace App\Filament\Sarpras\Pages;

use App\Models\Good;
use App\Models\GoodsType;
use Filament\Pages\Page;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Filament\Actions\Action;
use BackedEnum;
use Filament\Support\Icons\Heroicon;

class GoodsByType extends Page implements HasTable
{
    use InteractsWithTable;

    protected string $view = 'filament.sarpras.pages.goods-by-type';
    protected static ?string $navigationLabel = null;
    protected static bool $shouldRegisterNavigation = false;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArchiveBox;

    public GoodsType $goodsType;

    public function mount(int $typeId): void
    {
        $this->goodsType = GoodsType::with('category')->findOrFail($typeId);
    }

    public function getTitle(): string
    {
        return $this->goodsType->code . ' - ' . $this->goodsType->name;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Good::query()
                    ->where('goods_type_id', $this->goodsType->id)
                    ->with(['supplier'])
            )
            ->columns([
                ImageColumn::make('photo')
                    ->label('Foto')
                    ->disk('public')
                    ->circular()
                    ->size(40),

                TextColumn::make('name')
                    ->label('Nama Barang')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('brand')
                    ->label('Merk')
                    ->default('-'),

                TextColumn::make('specification')
                    ->label('Spesifikasi')
                    ->limit(30)
                    ->default('-'),

                TextColumn::make('unit')
                    ->label('Satuan'),

                TextColumn::make('quantity')
                    ->label('Total')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('stock')
                    ->label('Stok')
                    ->numeric()
                    ->sortable()
                    ->color(fn($state) => $state > 0 ? 'success' : 'danger'),

                TextColumn::make('funding_source')
                    ->label('Dana')
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        'BOS'     => 'info',
                        'BOSDA'   => 'success',
                        'Sekolah' => 'danger',
                        'Bantuan' => 'warning',
                        default   => 'gray',
                    })
                    ->default('-'),

                TextColumn::make('purchase_date')
                    ->label('Tgl Beli')
                    ->date('d M Y')
                    ->default('-')
                    ->toggleable(isToggledHiddenByDefault: true),

                IconColumn::make('is_consumable')
                    ->label('Habis Pakai')
                    ->boolean(),
            ])
            ->filters([
                TernaryFilter::make('is_consumable')
                    ->label('Habis Pakai'),

                SelectFilter::make('funding_source')
                    ->label('Sumber Dana')
                    ->options([
                        'BOS'     => 'BOS',
                        'BOSDA'   => 'BOSDA',
                        'Sekolah' => 'Sekolah',
                        'Bantuan' => 'Bantuan',
                    ]),
            ])
            ->headerActions([
                // ✅ Tombol kembali
                Action::make('back')
                    ->label('← Kembali')
                    ->color('gray')
                    ->url(fn() => route('filament.sarpras.resources.goods.index')),

                // ✅ Tombol tambah barang jenis ini
                Action::make('create')
                    ->label('Tambah Barang')
                    ->icon('heroicon-o-plus')
                    ->color('primary')
                    ->url(fn() => route('sarpras.goods.create', ['type_id' => $this->goodsType->id])),
            ])
            ->recordUrl(fn($record) => route('sarpras.goods.detail', $record->id));
    }
}
