<?php

namespace App\Filament\Sarpras\Resources\ProcurementRequests;

use App\Filament\Sarpras\Resources\ProcurementRequests\Pages\CreateProcurementRequest;
use App\Filament\Sarpras\Resources\ProcurementRequests\Pages\EditProcurementRequest;
use App\Filament\Sarpras\Resources\ProcurementRequests\Pages\ListProcurementRequests;
use App\Filament\Sarpras\Resources\ProcurementRequests\Pages\RealisasiPengajuan;
use App\Filament\Sarpras\Resources\ProcurementRequests\Pages\ViewProcurementRequest;
use App\Filament\Sarpras\Resources\ProcurementRequests\Schemas\ProcurementRequestForm;
use App\Filament\Sarpras\Resources\ProcurementRequests\Schemas\ProcurementRequestInfolist;
use App\Filament\Sarpras\Resources\ProcurementRequests\Tables\ProcurementRequestsTable;
use App\Models\ProcurementRequest;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class ProcurementRequestResource extends Resource
{
    protected static ?string $model = ProcurementRequest::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $navigationLabel = 'Pengajuan Barang';

    protected static ?string $modelLabel = 'Pengajuan';

    protected static ?string $pluralModelLabel = 'Pengajuan Barang';

    protected static string|UnitEnum|null $navigationGroup = 'Pengadaan';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return ProcurementRequestForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ProcurementRequestInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProcurementRequestsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListProcurementRequests::route('/'),
            'create' => CreateProcurementRequest::route('/create'),
            'view'   => ViewProcurementRequest::route('/{record}'),
            'edit'   => EditProcurementRequest::route('/{record}/edit'),
            'realisasi'  => RealisasiPengajuan::route('/{record}/realisasi'),
        ];
    }
}
