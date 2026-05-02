<?php

namespace App\Filament\Resources\RoomChecks\Schemas;

use App\Models\Infrastructure;
use App\Models\Location;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class RoomCheckForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
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
                                    ->numeric()
                                    ->dehydrated(true),

                                Radio::make('status')
                                    ->label('Status')
                                    ->options([
                                        'OK'         => '✅ OK',
                                        'Bermasalah' => '⚠️ Bermasalah',
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

                                Hidden::make('infrastructure_id'),
                                Hidden::make('total'),
                                Hidden::make('broken'),
                            ])
                            ->columns(2)
                            ->addable(false)
                            ->deletable(false)
                            ->reorderable(false),
                    ]),

                Section::make('Catatan')
                    ->schema([
                        Textarea::make('general_note')
                            ->label('Catatan Umum')
                            ->placeholder('Catatan tambahan untuk pengecekan ini...')
                            ->rows(3),
                    ]),
            ]);
    }
}
