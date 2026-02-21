<?php

namespace App\Filament\Resources\KardusMasuks\Pages;

use App\Filament\Resources\KardusMasuks\KardusMasukResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListKardusMasuks extends ListRecords
{
    protected static string $resource = KardusMasukResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
