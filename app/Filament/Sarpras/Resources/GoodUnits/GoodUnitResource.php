<?php

namespace App\Filament\Sarpras\Resources\GoodUnits;

use App\Filament\Sarpras\Resources\GoodUnits\Pages\ListGoodUnits;
use App\Filament\Sarpras\Resources\GoodUnits\Tables\GoodUnitsTable;
use App\Models\GoodUnit;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class GoodUnitResource extends Resource
{
    protected static ?string $model = GoodUnit::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedQrCode;

    protected static ?string $recordTitleAttribute = 'code';

    protected static ?string $navigationLabel = 'Kode Inventaris';

    protected static ?string $modelLabel = 'Kode Unit';

    protected static ?string $pluralModelLabel = 'Kode Inventaris';

    protected static string|UnitEnum|null $navigationGroup = 'Inventaris';

    protected static ?int $navigationSort = 3;

    protected static bool $shouldRegisterNavigation = false;

    public static function table(Table $table): Table
    {
        return GoodUnitsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListGoodUnits::route('/'),
        ];
    }
}
