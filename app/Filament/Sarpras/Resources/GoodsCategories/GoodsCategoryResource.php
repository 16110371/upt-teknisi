<?php

namespace App\Filament\Sarpras\Resources\GoodsCategories;

use App\Filament\Sarpras\Resources\GoodsCategories\Pages\CreateGoodsCategory;
use App\Filament\Sarpras\Resources\GoodsCategories\Pages\EditGoodsCategory;
use App\Filament\Sarpras\Resources\GoodsCategories\Pages\ListGoodsCategories;
use App\Filament\Sarpras\Resources\GoodsCategories\Schemas\GoodsCategoryForm;
use App\Filament\Sarpras\Resources\GoodsCategories\Tables\GoodsCategoriesTable;
use App\Models\GoodsCategory;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class GoodsCategoryResource extends Resource
{
    protected static ?string $model = GoodsCategory::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationLabel = 'Kategori Barang';

    protected static ?string $modelLabel = 'Kategori';

    protected static ?string $pluralModelLabel = 'Kategori Barang';

    protected static string|UnitEnum|null $navigationGroup = 'Master Data';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return GoodsCategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GoodsCategoriesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListGoodsCategories::route('/'),
            'create' => CreateGoodsCategory::route('/create'),
            'edit'   => EditGoodsCategory::route('/{record}/edit'),
        ];
    }
}
