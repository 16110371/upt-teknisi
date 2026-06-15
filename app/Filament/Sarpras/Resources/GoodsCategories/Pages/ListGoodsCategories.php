<?php

namespace App\Filament\Sarpras\Resources\GoodsCategories\Pages;

use App\Filament\Sarpras\Resources\GoodsCategories\GoodsCategoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListGoodsCategories extends ListRecords
{
    protected static string $resource = GoodsCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
