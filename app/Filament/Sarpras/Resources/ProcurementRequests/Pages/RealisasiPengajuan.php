<?php

namespace App\Filament\Sarpras\Resources\ProcurementRequests\Pages;

use App\Filament\Sarpras\Resources\ProcurementRequests\ProcurementRequestResource;
use App\Models\Good;
use App\Models\GoodsCategory;
use App\Models\ProcurementRequest;
use App\Models\Supplier;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\DB;

class RealisasiPengajuan extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = ProcurementRequestResource::class;
    protected string $view = 'filament.sarpras.resources.procurement-requests.pages.realisasi-pengajuan';
    protected static ?string $title = 'Realisasi Pengajuan';

    public ProcurementRequest $record;
    public ?array $data = [];

    public function mount(ProcurementRequest $record): void
    {
        $this->record = $record;

        // ✅ Pre-fill dari item pengajuan
        $this->form->fill([
            'items' => $record->items->map(fn($item) => [
                'procurement_item_id' => $item->id,
                'name'                => $item->name,
                'specification'       => $item->specification,
                'quantity'            => $item->quantity,
                'unit'                => $item->unit,
                'price'               => $item->estimated_price,
                'is_consumable'       => false,
                'goods_category_id'   => null,
                'supplier_id'         => null,
                'purchase_date'       => now()->format('Y-m-d'),
                'note'                => $item->note,
            ])->toArray(),
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Informasi Pengajuan')
                    ->schema([
                        TextInput::make('procurement_title')
                            ->label('Judul Pengajuan')
                            ->default(fn() => $this->record->title)
                            ->disabled()
                            ->dehydrated(false),

                        TextInput::make('requester')
                            ->label('Diajukan Oleh')
                            ->default(fn() => $this->record->user->name)
                            ->disabled()
                            ->dehydrated(false),
                    ])->columns(2),

                Section::make('Detail Barang yang Datang')
                    ->description('Input detail barang yang sudah datang')
                    ->schema([
                        Repeater::make('items')
                            ->label('')
                            ->schema([
                                TextInput::make('name')
                                    ->label('Nama Barang')
                                    ->required()
                                    ->maxLength(200),

                                Select::make('goods_category_id')
                                    ->label('Kategori')
                                    ->options(GoodsCategory::pluck('name', 'id'))
                                    ->searchable()
                                    ->required(),

                                TextInput::make('specification')
                                    ->label('Spesifikasi')
                                    ->nullable()
                                    ->columnSpanFull(),

                                TextInput::make('quantity')
                                    ->label('Jumlah')
                                    ->numeric()
                                    ->required()
                                    ->minValue(1),

                                TextInput::make('unit')
                                    ->label('Satuan')
                                    ->required(),

                                TextInput::make('price')
                                    ->label('Harga Satuan')
                                    ->numeric()
                                    ->nullable()
                                    ->prefix('Rp'),

                                Select::make('supplier_id')
                                    ->label('Supplier')
                                    ->options(Supplier::pluck('name', 'id'))
                                    ->searchable()
                                    ->nullable(),

                                DatePicker::make('purchase_date')
                                    ->label('Tanggal Pembelian')
                                    ->required()
                                    ->default(now()),

                                Toggle::make('is_consumable')
                                    ->label('Barang Habis Pakai')
                                    ->default(false),

                                Textarea::make('note')
                                    ->label('Catatan')
                                    ->nullable()
                                    ->rows(2)
                                    ->columnSpanFull(),

                                // ✅ Hidden - procurement_item_id
                                TextInput::make('procurement_item_id')
                                    ->hidden()
                                    ->dehydrated(true),
                            ])
                            ->columns(2)
                            ->addable(false)
                            ->deletable(false)
                            ->reorderable(false),
                    ]),
            ])
            ->statePath('data');
    }

    public function submit(): void
    {
        $data = $this->form->getState();

        DB::transaction(function () use ($data) {
            foreach ($data['items'] as $item) {
                // ✅ Generate kode inventaris
                $category  = GoodsCategory::find($item['goods_category_id']);
                $sequence  = Good::where('goods_category_id', $item['goods_category_id'])
                    ->count() + 1;
                $year      = now()->year;

                $code = sprintf(
                    '%s%s-SMKSW-%s-%03d',
                    strtoupper($category->code),
                    substr($year, 2),
                    $year,
                    $sequence
                );

                // ✅ Simpan ke master barang
                Good::create([
                    'code'                => $code,
                    'goods_category_id'   => $item['goods_category_id'],
                    'procurement_item_id' => $item['procurement_item_id'] ?? null,
                    'supplier_id'         => $item['supplier_id'] ?? null,
                    'name'                => $item['name'],
                    'specification'       => $item['specification'] ?? null,
                    'unit'                => $item['unit'],
                    'quantity'            => $item['quantity'],
                    'stock'               => $item['quantity'],
                    'price'               => $item['price'] ?? null,
                    'purchase_date'       => $item['purchase_date'],
                    'is_consumable'       => $item['is_consumable'] ?? false,
                    'note'                => $item['note'] ?? null,
                ]);
            }

            // ✅ Update status pengajuan ke Selesai
            $this->record->update(['status' => 'Selesai']);
        });

        Notification::make()
            ->title('Realisasi berhasil! Barang sudah masuk ke master data.')
            ->success()
            ->send();

        $this->redirect(ProcurementRequestResource::getUrl('index'));
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
