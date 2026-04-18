<?php

namespace App\Filament\Resources\Infrastructures\Pages;

use App\Filament\Resources\Infrastructures\InfrastructureResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditInfrastructure extends EditRecord
{
    protected static string $resource = InfrastructureResource::class;

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
}
