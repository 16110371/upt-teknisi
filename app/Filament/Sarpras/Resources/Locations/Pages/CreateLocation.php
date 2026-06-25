<?php

namespace App\Filament\Sarpras\Resources\Locations\Pages;

use App\Filament\Sarpras\Resources\Locations\LocationResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLocation extends CreateRecord
{
    protected static string $resource = LocationResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
