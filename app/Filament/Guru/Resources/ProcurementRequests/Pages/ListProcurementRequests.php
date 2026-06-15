<?php

namespace App\Filament\Guru\Resources\ProcurementRequests\Pages;

use App\Filament\Guru\Resources\ProcurementRequests\ProcurementRequestResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProcurementRequests extends ListRecords
{
    protected static string $resource = ProcurementRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
