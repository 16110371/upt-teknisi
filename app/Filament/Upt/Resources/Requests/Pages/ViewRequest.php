<?php

namespace App\Filament\Upt\Resources\Requests\Pages;

use App\Filament\Upt\Resources\Requests\RequestResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewRequest extends ViewRecord
{
    protected static string $resource = RequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
