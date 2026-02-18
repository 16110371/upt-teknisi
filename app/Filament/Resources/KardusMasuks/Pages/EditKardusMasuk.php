<?php

namespace App\Filament\Resources\KardusMasuks\Pages;

use App\Filament\Resources\KardusMasuks\KardusMasukResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditKardusMasuk extends EditRecord
{
    protected static string $resource = KardusMasukResource::class;

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
