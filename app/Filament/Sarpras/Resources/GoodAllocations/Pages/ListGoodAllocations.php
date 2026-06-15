<?php

namespace App\Filament\Sarpras\Resources\GoodAllocations\Pages;

use App\Filament\Sarpras\Resources\GoodAllocations\GoodAllocationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListGoodAllocations extends ListRecords
{
    protected static string $resource = GoodAllocationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
