<?php

namespace App\Filament\Resources\Infrastructures\Pages;

use App\Filament\Resources\Infrastructures\InfrastructureResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListInfrastructures extends ListRecords
{
    protected static string $resource = InfrastructureResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
