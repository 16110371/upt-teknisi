<?php

namespace App\Filament\Resources\Infrastructures;

use App\Filament\Resources\Infrastructures\Pages\CreateInfrastructure;
use App\Filament\Resources\Infrastructures\Pages\EditInfrastructure;
use App\Filament\Resources\Infrastructures\Pages\ListInfrastructures;
use App\Filament\Resources\Infrastructures\Schemas\InfrastructureForm;
use App\Filament\Resources\Infrastructures\Tables\InfrastructuresTable;
use App\Models\Infrastructure;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class InfrastructureResource extends Resource
{
    protected static ?string $model = Infrastructure::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice2;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationLabel = 'Infrastruktur';

    protected static ?string $modelLabel = 'Infrastruktur';

    protected static ?string $pluralModelLabel = 'Infrastruktur';

    protected static string|UnitEnum|null $navigationGroup = 'Pencatatan';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return InfrastructureForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return InfrastructuresTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListInfrastructures::route('/'),
            'create' => CreateInfrastructure::route('/create'),
            'edit'   => EditInfrastructure::route('/{record}/edit'),
        ];
    }
}
