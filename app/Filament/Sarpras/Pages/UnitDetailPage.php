<?php

namespace App\Filament\Sarpras\Pages;

use App\Models\GoodUnit;
use Filament\Actions\Action;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use BackedEnum;
use Filament\Support\Icons\Heroicon;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class UnitDetailPage extends Page implements HasTable, HasInfolists
{
    use InteractsWithTable;
    use InteractsWithInfolists;

    protected string $view = 'filament.sarpras.pages.unit-detail-page';
    protected static bool $shouldRegisterNavigation = false;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedQrCode;

    public GoodUnit $unit;

    public function mount(int $id): void
    {
        $this->unit = GoodUnit::with([
            'good.goodsType',
            'good.category',
            'good.supplier',
            'location',
        ])->findOrFail($id);
    }

    public function getTitle(): string
    {
        return $this->unit->code;
    }

    public function unitInfolist(Schema $schema): Schema
    {
        $qrSvg = QrCode::size(150)->format('svg')->generate($this->unit->code);
        $qrBase64 = base64_encode($qrSvg);
        return $schema
            ->record($this->unit)
            ->components([
                Section::make('Informasi Unit')
                    ->schema([
                        TextEntry::make('code')
                            ->label('Kode Inventaris')
                            ->badge()
                            ->color('success'),

                        TextEntry::make('location.name')
                            ->label('Lokasi'),

                        TextEntry::make('good.goodsType.name')
                            ->label('Jenis Barang'),

                        TextEntry::make('good.name')
                            ->label('Nama Barang'),

                        TextEntry::make('good.brand')
                            ->label('Merk')
                            ->placeholder('-'),

                        TextEntry::make('good.specification')
                            ->label('Spesifikasi')
                            ->placeholder('-'),

                        TextEntry::make('good.funding_source')
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

                        TextEntry::make('good.procurement_year')
                            ->label('Tahun Pengadaan')
                            ->placeholder('-'),

                        TextEntry::make('created_at')
                            ->label('Tgl Generate Kode')
                            ->dateTime('d M Y'),

                        \Filament\Infolists\Components\ViewEntry::make('qr_code')
                            ->label('QR Code')
                            ->view('filament.sarpras.components.qr-code')
                            ->viewData(['qrBase64' => $qrBase64])
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    // ✅ Tabel riwayat kerusakan dari UPT
    public function table(Table $table): Table
    {
        return $table
            ->query(
                \App\Models\RequestUnit::query()
                    ->where('unit_id', $this->unit->id)
                    ->with(['request.location', 'request.technicians'])
            )
            ->columns([
                TextColumn::make('type')
                    ->label('Tipe')
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        'rusak'      => 'danger',
                        'diperbaiki' => 'success',
                        'permanen'   => 'gray',
                        default      => 'gray',
                    })
                    ->formatStateUsing(fn($state) => match ($state) {
                        'rusak'      => '🔧 Rusak',
                        'diperbaiki' => '✅ Diperbaiki',
                        'permanen'   => '❌ Rusak Permanen',
                        default      => $state,
                    }),

                TextColumn::make('request.description')
                    ->label('Deskripsi')
                    ->limit(50)
                    ->default('-'),

                TextColumn::make('request.status')
                    ->label('Status Permintaan')
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        'Pending'          => 'warning',
                        'Dikerjakan'       => 'info',
                        'Menunggu Part'    => 'gray',
                        'Selesai'          => 'success',
                        'Tidak Diperbaiki' => 'danger',
                        default            => 'gray',
                    }),

                TextColumn::make('request.technicians.name')
                    ->label('Teknisi')
                    ->badge()
                    ->separator(',')
                    ->default('-'),

                TextColumn::make('request.request_date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([])
            ->headerActions([
                Action::make('back')
                    ->label('← Kembali')
                    ->color('gray')
                    ->url(fn() => route('sarpras.inventaris', [
                        'location_id' => $this->unit->location_id
                    ])),

                Action::make('print_qr')
                    ->label('🖨️ Cetak QR')
                    ->color('success')
                    ->icon('heroicon-o-qr-code')
                    ->url(fn() => route('sarpras.unit.qr', $this->unit->id))
                    ->openUrlInNewTab(),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
