<?php

namespace App\Filament\Resources\KardusMasuks\Pages;

use App\Filament\Resources\KardusMasuks\KardusMasukResource;
use Filament\Resources\Pages\CreateRecord;

class CreateKardusMasuk extends CreateRecord
{
    protected static string $resource = KardusMasukResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
