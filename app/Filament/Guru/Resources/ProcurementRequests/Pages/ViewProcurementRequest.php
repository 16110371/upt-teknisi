<?php

namespace App\Filament\Guru\Resources\ProcurementRequests\Pages;

use App\Filament\Guru\Resources\ProcurementRequests\ProcurementRequestResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewProcurementRequest extends ViewRecord
{
    protected static string $resource = ProcurementRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
