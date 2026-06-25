<?php

namespace App\Filament\Sarpras\Resources\Locations;

use App\Filament\Sarpras\Resources\Locations\Pages\CreateLocation;
use App\Filament\Sarpras\Resources\Locations\Pages\EditLocation;
use App\Filament\Sarpras\Resources\Locations\Pages\ListLocations;
use App\Filament\Sarpras\Resources\Locations\Schemas\LocationForm;
use App\Filament\Sarpras\Resources\Locations\Tables\LocationsTable;
use App\Models\Location;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class LocationResource extends Resource
{
    protected static ?string $model = Location::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMapPin;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationLabel = 'Kode Ruang';

    protected static ?string $modelLabel = 'Lokasi';

    protected static ?string $pluralModelLabel = 'Kode Ruang';

    protected static string|UnitEnum|null $navigationGroup = 'Master Data';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return LocationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LocationsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLocations::route('/'),
            'create' => CreateLocation::route('/create'),
            'edit'  => EditLocation::route('/{record}/edit'),

        ];
    }
}
