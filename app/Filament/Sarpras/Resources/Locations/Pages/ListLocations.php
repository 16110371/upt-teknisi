<?php

namespace App\Filament\Sarpras\Resources\Locations\Pages;

use App\Filament\Sarpras\Resources\Locations\LocationResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\CreateAction;

class ListLocations extends ListRecords
{
    protected static string $resource = LocationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Tambah Lokasi'),
        ];
    }
}
