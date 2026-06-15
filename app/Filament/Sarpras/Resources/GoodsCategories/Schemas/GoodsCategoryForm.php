<?php

namespace App\Filament\Sarpras\Resources\GoodsCategories\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class GoodsCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('code')
                    ->label('Kode Kategori')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(10)
                    ->placeholder('Contoh: A, B, C')
                    ->helperText('Kode singkat untuk kategori, dipakai dalam kode inventaris'),

                TextInput::make('name')
                    ->label('Nama Kategori')
                    ->required()
                    ->maxLength(100)
                    ->placeholder('Contoh: Alat, Bahan, Elektronik'),

                Textarea::make('description')
                    ->label('Deskripsi')
                    ->nullable()
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }
}
