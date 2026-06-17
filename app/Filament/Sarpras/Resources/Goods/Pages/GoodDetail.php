<?php

namespace App\Filament\Sarpras\Pages;

use App\Models\Good;
use App\Models\GoodUnit;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Actions\Action;
use BackedEnum;
use Filament\Support\Icons\Heroicon;

class GoodDetail extends Page implements HasTable
{
    use InteractsWithTable;

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

    public function table(Table $table): Table
    {
        return $table
            ->query(
                GoodUnit::query()
                    ->where('good_id', $this->good->id)
                    ->with(['location'])
            )
            ->columns([
                TextColumn::make('code')
                    ->label('Kode Inventaris')
                    ->searchable()
                    ->badge()
                    ->color('success'),

                TextColumn::make('location.name')
                    ->label('Lokasi')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('created_at')
                    ->label('Tgl Generate')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('location_id')
                    ->label('Lokasi')
                    ->relationship('location', 'name'),
            ])
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
            ]);
    }
}
