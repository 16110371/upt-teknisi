<?php

namespace App\Filament\Sarpras\Resources\Goods;

use App\Filament\Sarpras\Resources\Goods\Pages\CreateGood;
use App\Filament\Sarpras\Resources\Goods\Pages\EditGood;
use App\Filament\Sarpras\Resources\Goods\Pages\ListGoods;
use App\Filament\Sarpras\Resources\Goods\Schemas\GoodForm;
use App\Filament\Sarpras\Resources\Goods\Tables\GoodsTable;
use App\Models\Good;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class GoodResource extends Resource
{
    protected static ?string $model = Good::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArchiveBox;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationLabel = 'Data Barang';

    protected static ?string $modelLabel = 'Barang';

    protected static ?string $pluralModelLabel = 'Data Barang';

    protected static string|UnitEnum|null $navigationGroup = 'Inventaris';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return GoodForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GoodsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListGoods::route('/'),
            'create' => CreateGood::route('/create'),
            'edit'   => EditGood::route('/{record}/edit'),
        ];
    }
}
