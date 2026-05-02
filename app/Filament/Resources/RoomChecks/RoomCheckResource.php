<?php

namespace App\Filament\Resources\RoomChecks;

use App\Filament\Resources\RoomChecks\Pages\CreateRoomCheck;
use App\Filament\Resources\RoomChecks\Pages\EditRoomCheck;
use App\Filament\Resources\RoomChecks\Pages\ListRoomChecks;
use App\Filament\Resources\RoomChecks\Pages\ViewRoomCheck;
use App\Filament\Resources\RoomChecks\Schemas\RoomCheckForm;
use App\Filament\Resources\RoomChecks\Tables\RoomChecksTable;
use App\Models\RoomCheck;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class RoomCheckResource extends Resource
{
    protected static ?string $model = RoomCheck::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentCheck;

    protected static ?string $recordTitleAttribute = 'id';

    protected static ?string $navigationLabel = 'Riwayat Pengecekan';

    protected static ?string $modelLabel = 'Pengecekan';

    protected static ?string $pluralModelLabel = 'Riwayat Pengecekan';

    protected static string|UnitEnum|null $navigationGroup = 'Pencatatan';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return RoomCheckForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RoomChecksTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListRoomChecks::route('/'),
            'create' => CreateRoomCheck::route('/create'),
            'edit'   => EditRoomCheck::route('/{record}/edit'),
            'view'   => ViewRoomCheck::route('/{record}'), // ✅ tambah
        ];
    }
}
