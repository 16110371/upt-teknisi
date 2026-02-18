<?php

namespace App\Filament\Resources\KardusMasuks;

use App\Filament\Resources\KardusMasuks\Pages\CreateKardusMasuk;
use App\Filament\Resources\KardusMasuks\Pages\EditKardusMasuk;
use App\Filament\Resources\KardusMasuks\Pages\ListKardusMasuks;
use App\Filament\Resources\KardusMasuks\Schemas\KardusMasukForm;
use App\Filament\Resources\KardusMasuks\Tables\KardusMasuksTable;
use App\Models\KardusMasuk;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class KardusMasukResource extends Resource
{
    protected static ?string $model = KardusMasuk::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string | UnitEnum | null $navigationGroup = 'Pencatatan';

    protected static ?string $recordTitleAttribute = 'nama_kardus';

    public static function form(Schema $schema): Schema
    {
        return KardusMasukForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return KardusMasuksTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListKardusMasuks::route('/'),
            'create' => CreateKardusMasuk::route('/create'),
            'edit' => EditKardusMasuk::route('/{record}/edit'),
        ];
    }
}
