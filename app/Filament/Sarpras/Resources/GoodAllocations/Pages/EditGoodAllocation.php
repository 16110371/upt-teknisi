<?php

namespace App\Filament\Sarpras\Resources\GoodAllocations\Pages;

use App\Filament\Sarpras\Resources\GoodAllocations\GoodAllocationResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditGoodAllocation extends EditRecord
{
    protected static string $resource = GoodAllocationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
