<?php

namespace App\Filament\Resources\KardusMasuks\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class KardusMasukForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            DatePicker::make('tanggal_masuk')
                ->required(),

            TextInput::make('nama_kardus')
                ->required()
                ->maxLength(100),

            FileUpload::make('foto')
                ->image()
                ->optimize('webp')
                ->resize(50)
                ->maxImageWidth(1200)
                ->maxImageHeight(1200)
                ->disk('public')
                ->directory('kardus')
                ->label('Foto Kardus'),

            Textarea::make('keterangan')
                ->rows(3),
        ]);
    }
}
