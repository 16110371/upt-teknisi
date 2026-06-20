<?php

namespace App\Filament\Sarpras\Resources\GoodsTypes;

use App\Filament\Sarpras\Resources\GoodsTypes\Pages\CreateGoodsType;
use App\Filament\Sarpras\Resources\GoodsTypes\Pages\EditGoodsType;
use App\Filament\Sarpras\Resources\GoodsTypes\Pages\ListGoodsTypes;
use App\Filament\Sarpras\Resources\GoodsTypes\Schemas\GoodsTypeForm;
use App\Filament\Sarpras\Resources\GoodsTypes\Tables\GoodsTypesTable;
use App\Models\GoodsType;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class GoodsTypeResource extends Resource
{
    protected static ?string $model = GoodsType::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationLabel = 'Jenis Barang';

    protected static ?string $modelLabel = 'Jenis Barang';

    protected static ?string $pluralModelLabel = 'Jenis Barang';

    protected static string|UnitEnum|null $navigationGroup = 'Master Data';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return GoodsTypeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GoodsTypesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListGoodsTypes::route('/'),
            'create' => CreateGoodsType::route('/create'),
            'edit'   => EditGoodsType::route('/{record}/edit'),
        ];
    }
}
