<?php

namespace App\Filament\Sarpras\Resources\Goods\Pages;

use App\Filament\Sarpras\Resources\Goods\GoodResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListGoods extends ListRecords
{
    protected static string $resource = GoodResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
