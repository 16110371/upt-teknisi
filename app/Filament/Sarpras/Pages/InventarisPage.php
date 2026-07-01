<?php

namespace App\Filament\Sarpras\Pages;

use App\Models\GoodUnit;
use App\Models\Location;
use Filament\Navigation\NavigationItem;
use Filament\Pages\Concerns\HasSubNavigation;
use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Actions\Action;
use BackedEnum;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class InventarisPage extends Page implements HasTable
{
    use InteractsWithTable;
    use HasSubNavigation;

    protected string $view = 'filament.sarpras.pages.inventaris-page';
    protected static ?string $navigationLabel = 'Inventaris';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedQrCode;
    protected static string|UnitEnum|null $navigationGroup = 'Inventaris';
    protected static ?int $navigationSort = 3;
    public static function getSubNavigationPosition(): SubNavigationPosition
    {
        return SubNavigationPosition::Top;
    }

    public ?int $locationId = null;

    public function mount(): void
    {
        // ✅ Default ke lokasi pertama
        $this->locationId = request('location_id')
            ?? Location::first()?->id;
    }

    public function getTitle(): string
    {
        if ($this->locationId) {
            return Location::find($this->locationId)?->name ?? 'Kode Inventaris';
        }
        return 'Kode Inventaris';
    }

    // ✅ Generate sub-navigation per lokasi
    public function getSubNavigation(): array
    {
        $locations = Location::all();
        $items = [];

        foreach ($locations as $location) {
            $count = GoodUnit::where('location_id', $location->id)->count();

            $items[] = NavigationItem::make($location->name)
                ->icon(Heroicon::OutlinedMapPin)
                ->badge($count > 0 ? $count : null)
                ->url(route('sarpras.inventaris', ['location_id' => $location->id]))
                ->isActiveWhen(fn() => $this->locationId == $location->id);
        }

        return $items;
    }

    public function getStats(): array
    {
        $query = GoodUnit::where('location_id', $this->locationId);

        return [
            'total'     => (clone $query)->count(),
            'good'      => (clone $query)->where('status', 'good')->count(),
            'broken'    => (clone $query)->where('status', 'broken')->count(),
            'permanent' => (clone $query)->where('status', 'permanent_broken')->count(),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                GoodUnit::query()
                    ->where('location_id', $this->locationId)
                    ->with(['good.goodsType', 'good.category', 'location'])
            )
            ->columns([
                TextColumn::make('code')
                    ->label('Kode Inventaris')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('success'),

                TextColumn::make('good.name')
                    ->label('Nama Barang')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('good.goodsType.name')
                    ->label('Jenis')
                    ->sortable(),

                TextColumn::make('good.brand')
                    ->label('Merk')
                    ->default('-'),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        'good'             => 'success',
                        'broken'           => 'warning',
                        'permanent_broken' => 'danger',
                        default            => 'gray',
                    })
                    ->formatStateUsing(fn($state) => match ($state) {
                        'good'             => '✅ Baik',
                        'broken'           => '🔧 Rusak',
                        'permanent_broken' => '❌ Permanen',
                        default            => $state,
                    }),

                TextColumn::make('good.funding_source')
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

                TextColumn::make('created_at')
                    ->label('Tgl Generate')
                    ->date('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('good.goods_category_id')
                    ->label('Kategori')
                    ->relationship('good.category', 'name'),
            ])
            ->recordUrl(fn($record) => route('sarpras.inventaris.unit', $record->id))
            ->recordActions([
                Action::make('print_qr')
                    ->label('QR')
                    ->icon('heroicon-o-qr-code')
                    ->color('success')
                    ->url(fn($record) => route('sarpras.unit.qr', $record->id))
                    ->openUrlInNewTab(),
            ])
            ->toolbarActions([
                \Filament\Actions\BulkAction::make('print_selected_qr')
                    ->label('Cetak QR Terpilih')
                    ->icon('heroicon-o-printer')
                    ->color('success')
                    ->action(function ($records, \Filament\Pages\Page $livewire) {
                        $ids = $records->pluck('id')->implode(',');
                        $url = route('sarpras.units.qr.bulk', ['ids' => $ids]);

                        // Menggunakan Livewire dispatch untuk membuka tab baru di sisi browser
                        $livewire->js("window.open('{$url}', '_blank')");
                    }),
            ]);
    }
}
