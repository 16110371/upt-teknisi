<?php

namespace App\Filament\Resources\RoomChecks\Pages;

use App\Filament\Resources\RoomChecks\RoomCheckResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRoomChecks extends ListRecords
{
    protected static string $resource = RoomCheckResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
