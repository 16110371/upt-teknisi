<?php

namespace App\Filament\Sarpras\Resources\Locations\Pages;

use App\Filament\Sarpras\Resources\Locations\LocationResource;
use Filament\Resources\Pages\ListRecords;

class ListLocations extends ListRecords
{
    protected static string $resource = LocationResource::class;

    protected function getHeaderActions(): array
    {
        return []; // ✅ tidak ada tombol create
    }
}
