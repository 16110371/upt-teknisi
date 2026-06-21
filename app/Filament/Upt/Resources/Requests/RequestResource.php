<?php

namespace App\Filament\Upt\Resources\Requests;

use App\Filament\Upt\Resources\Requests\Pages\CreateRequest;
use App\Filament\Upt\Resources\Requests\Pages\EditRequest;
use App\Filament\Upt\Resources\Requests\Pages\ListRequests;
use App\Filament\Upt\Resources\Requests\Pages\ViewRequest;
use App\Filament\Upt\Resources\Requests\Schemas\RequestForm;
use App\Filament\Upt\Resources\Requests\Schemas\RequestInfolist;
use App\Filament\Upt\Resources\Requests\Tables\RequestsTable;
use App\Models\Request;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class RequestResource extends Resource
{
    protected static ?string $model = Request::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static ?string $recordTitleAttribute = 'requester_name';

    protected static ?string $navigationLabel = 'Permintaan';

    protected static ?string $modelLabel = 'Permintaan';

    protected static ?string $pluralModelLabel = 'Permintaan';

    protected static string|UnitEnum|null $navigationGroup = 'Data';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return RequestForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return RequestInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RequestsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListRequests::route('/'),
            'create' => CreateRequest::route('/create'),
            'view'   => ViewRequest::route('/{record}'),
            'edit'   => EditRequest::route('/{record}/edit'),
        ];
    }
}
