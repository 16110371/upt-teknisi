<?php

namespace App\Filament\Staff\Pages;

use App\Models\Infrastructure;
use App\Models\Location;
use App\Models\Request;
use App\Models\RoomCheck;
use App\Models\RoomCheckItem;
use BackedEnum;
use Filament\Forms\Components\Repeater;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Radio;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;
use Filament\Actions\Action;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Hidden;

class RoomCheckPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected string $view = 'filament.staff.pages.room-check-page';
    protected static ?string $navigationLabel = 'Pengecekan Ruang';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentCheck;
    protected static ?string $title = 'Pengecekan Ruang';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Lokasi')
                    ->description('Pilih lokasi yang akan diperiksa')
                    ->schema([
                        Select::make('location_id')
                            ->label('Lokasi')
                            ->options(Location::pluck('name', 'id'))
                            ->searchable()
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if (!$state) {
                                    $set('items', []);
                                    return;
                                }

                                $infrastructures = Infrastructure::with('category')
                                    ->where('location_id', $state)
                                    ->get();

                                $items = $infrastructures->map(fn($infra) => [
                                    'infrastructure_id' => $infra->id,
                                    'name'              => $infra->name,
                                    'category'          => $infra->category->name,
                                    'total'             => $infra->total,
                                    'good'              => $infra->good,
                                    'broken'            => $infra->broken,
                                    'status'            => 'OK',
                                    'quantity'          => 1,
                                    'note'              => '',
                                ])->toArray();

                                $set('items', $items);
                            }),
                    ]),

                Section::make('Checklist Item')
                    ->description('Periksa kondisi setiap item')
                    ->schema([
                        Repeater::make('items')
                            ->label('')
                            ->schema([
                                TextInput::make('name')
                                    ->label('Item')
                                    ->readOnly()
                                    ->dehydrated(true),

                                TextInput::make('category')
                                    ->label('Kategori')
                                    ->readOnly()
                                    ->dehydrated(true),

                                TextInput::make('good')
                                    ->label('Kondisi Baik')
                                    ->readOnly()
                                    ->dehydrated(true)
                                    ->numeric(),

                                Radio::make('status')
                                    ->label('Status')
                                    ->options([
                                        'OK'          => '✅ OK',
                                        'Bermasalah'  => '⚠️ Bermasalah',
                                    ])
                                    ->default('OK')
                                    ->inline()
                                    ->live(),

                                TextInput::make('quantity')
                                    ->label('Jumlah Bermasalah')
                                    ->numeric()
                                    ->default(1)
                                    ->minValue(1)
                                    ->visible(fn($get) => $get('status') === 'Bermasalah'),

                                Textarea::make('note')
                                    ->label('Keterangan Masalah')
                                    ->placeholder('Jelaskan masalah yang ditemukan...')
                                    ->rows(2)
                                    ->required(fn($get) => $get('status') === 'Bermasalah')
                                    ->visible(fn($get) => $get('status') === 'Bermasalah'),

                                // Hidden fields
                                Hidden::make('infrastructure_id'),
                                Hidden::make('total'),
                                Hidden::make('broken'),
                            ])
                            ->columns(2)
                            ->addable(false)
                            ->deletable(false)
                            ->reorderable(false),
                    ])
                    ->visible(fn($get) => filled($this->data['location_id'] ?? null)),

                Section::make('Catatan')
                    ->schema([
                        Textarea::make('general_note')
                            ->label('Catatan Umum')
                            ->placeholder('Catatan tambahan untuk pengecekan ini...')
                            ->rows(3),
                    ]),
            ])
            ->statePath('data');
    }

    public function submit(): void
    {
        $data = $this->form->getState();
        // dd($data['items']);
        if (empty($data['location_id'])) {
            Notification::make()
                ->title('Pilih lokasi terlebih dahulu')
                ->warning()
                ->send();
            return;
        }

        if (empty($data['items'])) {
            Notification::make()
                ->title('Tidak ada item untuk dicek')
                ->warning()
                ->send();
            return;
        }

        // ✅ Simpan room check
        $roomCheck = RoomCheck::create([
            'location_id' => $data['location_id'],
            'user_id'     => Auth::id(),
            'note'        => $data['general_note'] ?? '',
        ]);

        foreach ($data['items'] as $item) {
            $quantity = (int) ($item['quantity'] ?? 1);

            RoomCheckItem::create([
                'room_check_id'     => $roomCheck->id,
                'infrastructure_id' => $item['infrastructure_id'],
                'status'            => $item['status'],
                'quantity'          => $item['status'] === 'Bermasalah' ? (int)($item['quantity'] ?? 1) : 0,
                'note'              => $item['note'] ?? '',
            ]);

            if ($item['status'] === 'Bermasalah') {
                $infra = Infrastructure::find($item['infrastructure_id']);

                // ✅ Kurangi good, tambah broken
                $infra->update([
                    'good'   => max(0, $infra->good - $quantity),
                    'broken' => $infra->broken + $quantity,
                ]);

                // ✅ Buat request otomatis
                Request::create([
                    'request_date'      => now(),
                    'requester_name'    => Auth::user()->name,
                    'category_id'       => $infra->category_id,
                    'location_id'       => $data['location_id'],
                    'infrastructure_id' => $item['infrastructure_id'],
                    'damaged_quantity'  => $quantity,
                    'from_room_check'   => true,
                    'description'       => 'Ditemukan masalah saat pengecekan ruang: ' . ($item['note'] ?: 'Tidak ada keterangan'),
                    'status'            => 'Pending',
                    'priority'          => 'Rendah',
                ]);
            }
        }

        Notification::make()
            ->title('Pengecekan berhasil disimpan!')
            ->success()
            ->send();

        // ✅ Reset form
        $this->form->fill();
    }
}
