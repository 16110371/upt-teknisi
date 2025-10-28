<?php

namespace App\Filament\Resources\Requests\Pages;

use App\Filament\Resources\Requests\RequestResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Actions;

class CreateRequest extends CreateRecord
{
    protected static string $resource = RequestResource::class;
}
