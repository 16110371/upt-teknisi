<?php

namespace App\Filament\Sarpras\Resources\GoodsCategories\Pages;

use App\Filament\Sarpras\Resources\GoodsCategories\GoodsCategoryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditGoodsCategory extends EditRecord
{
    protected static string $resource = GoodsCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
