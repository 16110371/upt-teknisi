<?php

namespace App\Filament\Resources\RoomChecks\Pages;

use App\Filament\Resources\RoomChecks\RoomCheckResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditRoomCheck extends EditRecord
{
    protected static string $resource = RoomCheckResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
