<?php

namespace App\Filament\Sarpras\Resources\GoodsTypes\Pages;

use App\Filament\Sarpras\Resources\GoodsTypes\GoodsTypeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditGoodsType extends EditRecord
{
    protected static string $resource = GoodsTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
