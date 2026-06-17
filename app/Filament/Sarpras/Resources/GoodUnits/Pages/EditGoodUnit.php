<?php

namespace App\Filament\Sarpras\Resources\GoodUnits\Pages;

use App\Filament\Sarpras\Resources\GoodUnits\GoodUnitResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditGoodUnit extends EditRecord
{
    protected static string $resource = GoodUnitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
