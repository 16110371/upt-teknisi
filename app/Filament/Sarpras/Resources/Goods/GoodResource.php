<?php

namespace App\Filament\Sarpras\Resources\Goods;

use App\Filament\Sarpras\Resources\Goods\Pages\ListGoods;
use App\Models\GoodsType;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;
use App\Filament\Sarpras\Resources\Goods\Tables\GoodsTypeTable;
use App\Filament\Sarpras\Resources\Goods\Pages\CreateGood;
use App\Filament\Sarpras\Resources\Goods\Pages\EditGood;

class GoodResource extends Resource
{
    // ✅ Model diganti ke GoodsType (Level 1)
    protected static ?string $model = GoodsType::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArchiveBox;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationLabel = 'Data Barang';

    protected static ?string $modelLabel = 'Jenis Barang';

    protected static ?string $pluralModelLabel = 'Data Barang';

    protected static string|UnitEnum|null $navigationGroup = 'Inventaris';

    protected static ?int $navigationSort = 1;

    public static function table(Table $table): Table
    {
        return GoodsTypeTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListGoods::route('/'),
        ];
    }
}
