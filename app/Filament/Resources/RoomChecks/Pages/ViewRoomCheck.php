<?php

namespace App\Filament\Resources\RoomChecks\Pages;

use App\Filament\Resources\RoomChecks\RoomCheckResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewRoomCheck extends ViewRecord
{
    protected static string $resource = RoomCheckResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
