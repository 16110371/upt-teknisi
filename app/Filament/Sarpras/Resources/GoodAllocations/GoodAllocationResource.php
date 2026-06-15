<?php

namespace App\Filament\Sarpras\Resources\GoodAllocations;

use App\Filament\Sarpras\Resources\GoodAllocations\Pages\CreateGoodAllocation;
use App\Filament\Sarpras\Resources\GoodAllocations\Pages\EditGoodAllocation;
use App\Filament\Sarpras\Resources\GoodAllocations\Pages\ListGoodAllocations;
use App\Filament\Sarpras\Resources\GoodAllocations\Schemas\GoodAllocationForm;
use App\Filament\Sarpras\Resources\GoodAllocations\Tables\GoodAllocationsTable;
use App\Models\GoodAllocation;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class GoodAllocationResource extends Resource
{
    protected static ?string $model = GoodAllocation::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowRightOnRectangle;

    protected static ?string $recordTitleAttribute = 'id';

    protected static ?string $navigationLabel = 'Alokasi Barang';

    protected static ?string $modelLabel = 'Alokasi';

    protected static ?string $pluralModelLabel = 'Alokasi Barang';

    protected static string|UnitEnum|null $navigationGroup = 'Inventaris';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return GoodAllocationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GoodAllocationsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListGoodAllocations::route('/'),
            'create' => CreateGoodAllocation::route('/create'),
            'edit'   => EditGoodAllocation::route('/{record}/edit'),
        ];
    }
}
