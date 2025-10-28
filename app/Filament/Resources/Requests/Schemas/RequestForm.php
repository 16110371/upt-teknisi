<?php

namespace App\Filament\Resources\Requests\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class RequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                DatePicker::make('request_date')
                    ->required(),
                TextInput::make('requester_name')
                    ->label('Nama')
                    ->required(),
                TextInput::make('requester_contact'),
                Select::make('category_id')
                    ->label('Kategori')
                    ->relationship('category', 'name')
                    ->required(),
                Select::make('location_id')
                    ->label('Lokasi')
                    ->relationship('location', 'name')
                    ->required(),
                Select::make('status')
                    ->options(['Pending' => 'Pending', 'Dikerjakan' => 'Dikerjakan', 'Selesai' => 'Selesai'])
                    ->default('Pending')
                    ->required(),
                Select::make('technician_id')
                    ->label('Teknisi')
                    ->relationship('technician', 'name'),
                DateTimePicker::make('handled_at'),
                FileUpload::make('photo')
                    ->label('Foto Kerusakan'),
                Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }
}
