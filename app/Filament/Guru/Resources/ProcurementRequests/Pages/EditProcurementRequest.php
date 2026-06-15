<?php

namespace App\Filament\Guru\Resources\ProcurementRequests\Pages;

use App\Filament\Guru\Resources\ProcurementRequests\ProcurementRequestResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditProcurementRequest extends EditRecord
{
    protected static string $resource = ProcurementRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
