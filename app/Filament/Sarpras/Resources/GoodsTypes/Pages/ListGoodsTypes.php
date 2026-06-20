<?php

namespace App\Filament\Sarpras\Resources\GoodsTypes\Pages;

use App\Filament\Sarpras\Resources\GoodsTypes\GoodsTypeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListGoodsTypes extends ListRecords
{
    protected static string $resource = GoodsTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
