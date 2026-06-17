<?php

namespace App\Filament\Sarpras\Resources\GoodUnits\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class GoodUnitForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('good_id')
                    ->required()
                    ->numeric(),
                TextInput::make('good_allocation_id')
                    ->numeric(),
                TextInput::make('location_id')
                    ->required()
                    ->numeric(),
                TextInput::make('code')
                    ->required(),
            ]);
    }
}
