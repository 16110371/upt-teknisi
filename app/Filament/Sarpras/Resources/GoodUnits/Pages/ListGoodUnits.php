<?php

namespace App\Filament\Sarpras\Resources\GoodUnits\Pages;

use App\Filament\Sarpras\Resources\GoodUnits\GoodUnitResource;
use Filament\Resources\Pages\ListRecords;

class ListGoodUnits extends ListRecords
{
    protected static string $resource = GoodUnitResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
