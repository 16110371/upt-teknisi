<?php

namespace App\Filament\Sarpras\Resources\Goods\Pages;

use App\Filament\Sarpras\Resources\Goods\GoodResource;
use Filament\Resources\Pages\CreateRecord;

class CreateGood extends CreateRecord
{
    protected static string $resource = GoodResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if ($data['unit'] === 'lainnya' && !empty($data['unit_custom'])) {
            $data['unit'] = $data['unit_custom'];
        }
        unset($data['unit_custom']);

        return $data;
    }
}
