<?php

namespace App\Filament\Sarpras\Resources\Goods\Pages;

use App\Filament\Sarpras\Resources\Goods\GoodResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditGood extends EditRecord
{
    protected static string $resource = GoodResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $presets = ['pcs', 'unit', 'set', 'box', 'dus', 'rim', 'lusin', 'pack', 'roll', 'botol', 'buah', 'meter', 'lembar'];

        if (!in_array($data['unit'], $presets)) {
            $data['unit_custom'] = $data['unit'];
            $data['unit'] = 'lainnya';
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if ($data['unit'] === 'lainnya' && !empty($data['unit_custom'])) {
            $data['unit'] = $data['unit_custom'];
        }
        unset($data['unit_custom']);

        return $data;
    }
}
