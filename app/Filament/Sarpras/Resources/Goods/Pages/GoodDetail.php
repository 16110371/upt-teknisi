<?php

namespace App\Filament\Sarpras\Resources\Goods\Pages;

use App\Models\Good;
use App\Models\GoodUnit;
use Filament\Actions\Action;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use BackedEnum;
use Filament\Support\Icons\Heroicon;

class GoodDetail extends Page implements HasTable, HasInfolists
{
    use InteractsWithTable;
    use InteractsWithInfolists;

    protected string $view = 'filament.sarpras.pages.good-detail';
    protected static bool $shouldRegisterNavigation = false;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArchiveBox;

    public Good $good;

    public function mount(int $goodId): void
    {
        $this->good = Good::with([
            'category',
            'goodsType',
            'supplier',
        ])->findOrFail($goodId);
    }

    public function getTitle(): string
    {
        return $this->good->name;
    }

    public function goodInfolist(Schema $schema): Schema
    {
        return $schema
            ->record($this->good)
            ->components([
                Section::make('Informasi Barang')
                    ->schema([
                        \Filament\Infolists\Components\ImageEntry::make('photo')
                            ->label('Foto')
                            ->disk('public')
                            ->height(120)
                            ->circular()
                            ->columnSpanFull(),

                        TextEntry::make('code')
                            ->label('Kode Jenis'),

                        TextEntry::make('goodsType.name')
                            ->label('Jenis Barang'),

                        TextEntry::make('category.name')
                            ->label('Kategori'),

                        TextEntry::make('brand')
                            ->label('Merk')
                            ->placeholder('-'),

                        TextEntry::make('specification')
                            ->label('Spesifikasi')
                            ->placeholder('-')
                            ->columnSpanFull(),

                        TextEntry::make('funding_source')
                            ->label('Sumber Dana')
                            ->badge()
                            ->color(fn($state) => match ($state) {
                                'BOS'     => 'info',
                                'BOSDA'   => 'success',
                                'Sekolah' => 'danger',
                                'Bantuan' => 'warning',
                                default   => 'gray',
                            })
                            ->placeholder('-'),

                        TextEntry::make('is_consumable')
                            ->label('Habis Pakai')
                            ->formatStateUsing(fn($state) => $state ? '✅ Ya' : '❌ Tidak'),
                    ])->columns(2),

                Section::make('Detail Pengadaan & Stok')
                    ->schema([
                        TextEntry::make('supplier.name')
                            ->label('Supplier')
                            ->placeholder('-'),

                        TextEntry::make('purchase_date')
                            ->label('Tanggal Pembelian')
                            ->date('d M Y')
                            ->placeholder('-'),

                        TextEntry::make('price')
                            ->label('Harga Satuan')
                            ->money('IDR')
                            ->placeholder('-'),

                        TextEntry::make('procurement_year')
                            ->label('Tahun Pengadaan')
                            ->placeholder('-'),

                        TextEntry::make('unit')
                            ->label('Satuan'),

                        TextEntry::make('quantity')
                            ->label('Total Unit')
                            ->suffix(fn() => ' ' . $this->good->unit),

                        TextEntry::make('stock')
                            ->label('Stok Tersedia')
                            ->suffix(fn() => ' ' . $this->good->unit)
                            ->color(fn($state) => $state > 0 ? 'success' : 'danger'),

                        TextEntry::make('note')
                            ->label('Catatan')
                            ->placeholder('-')
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                \App\Models\GoodAllocation::query()
                    ->where('good_id', $this->good->id)
                    ->with(['location', 'user'])
            )
            ->columns([
                TextColumn::make('location.name')
                    ->label('Dialokasikan ke')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('quantity')
                    ->label('Jumlah')
                    ->numeric(),

                TextColumn::make('user.name')
                    ->label('Oleh')
                    ->default('-'),

                TextColumn::make('allocation_date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('note')
                    ->label('Catatan')
                    ->default('-')
                    ->limit(30),
            ])
            ->filters([])
            ->recordActions([
                Action::make('print_qr')
                    ->label('QR')
                    ->icon('heroicon-o-qr-code')
                    ->color('success')
                    ->url(fn($record) => route('sarpras.unit.qr', $record->id))
                    ->openUrlInNewTab(),
            ])
            ->headerActions([
                Action::make('back')
                    ->label('← Kembali')
                    ->color('gray')
                    ->url(fn() => route('sarpras.goods.by-type', $this->good->goods_type_id)),

                Action::make('alokasi')
                    ->label('Alokasikan')
                    ->icon('heroicon-o-arrow-right-on-rectangle')
                    ->color('info')
                    ->visible(fn() => $this->good->stock > 0)
                    ->url(fn() => route('filament.sarpras.resources.good-allocations.create') . '?good_id=' . $this->good->id),

            ]);
    }
}
