<?php

namespace App\Filament\Resources\Infrastructures\Schemas;

use App\Models\Category;
use App\Models\Location;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class InfrastructureForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('location_id')
                    ->label('Lokasi')
                    ->options(Location::pluck('name', 'id'))
                    ->searchable()
                    ->required(),

                Select::make('category_id')
                    ->label('Kategori')
                    ->options(Category::pluck('name', 'id'))
                    ->searchable()
                    ->required(),

                TextInput::make('name')
                    ->label('Nama Item')
                    ->placeholder('Contoh: Komputer Acer i5')
                    ->required()
                    ->maxLength(255),

                TextInput::make('total')
                    ->label('Total Keseluruhan')
                    ->numeric()
                    ->default(0)
                    ->minValue(0)
                    ->required(),

                TextInput::make('good')
                    ->label('Kondisi Baik')
                    ->numeric()
                    ->default(0)
                    ->minValue(0)
                    ->required(),

                TextInput::make('broken')
                    ->label('Kondisi Rusak')
                    ->numeric()
                    ->default(0)
                    ->minValue(0)
                    ->required(),

                TextInput::make('permanent_broken')
                    ->label('Rusak Permanen')
                    ->numeric()
                    ->default(0)
                    ->minValue(0)
                    ->required()
                    ->helperText('Jumlah item yang tidak dapat diperbaiki'),

                Textarea::make('note')
                    ->label('Catatan')
                    ->nullable()
                    ->columnSpanFull(),
            ]);
    }
}
