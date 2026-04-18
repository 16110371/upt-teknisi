<?php

namespace App\Filament\Resources\Infrastructures\Pages;

use App\Filament\Resources\Infrastructures\InfrastructureResource;
use Filament\Resources\Pages\CreateRecord;

class CreateInfrastructure extends CreateRecord
{
    protected static string $resource = InfrastructureResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
