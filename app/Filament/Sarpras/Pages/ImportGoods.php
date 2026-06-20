<?php

namespace App\Filament\Sarpras\Pages;

use App\Imports\GoodsImport;
use App\Models\Good;
use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Maatwebsite\Excel\Facades\Excel;
use UnitEnum;

class ImportGoods extends Page implements HasForms
{
    use InteractsWithForms;

    protected string $view             = 'filament.sarpras.pages.import-goods';
    protected static ?string $navigationLabel = 'Import Barang';
    protected static ?string $title    = 'Import Data Barang';
    protected static string|UnitEnum|null $navigationGroup = 'Inventaris';
    protected static ?int $navigationSort = 10;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowUpTray;
    protected static bool $shouldRegisterNavigation = false;
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Upload File Excel')
                    ->description('Upload file Excel dengan format yang sesuai')
                    ->schema([
                        FileUpload::make('file')
                            ->label('File Excel (.xlsx / .csv)')
                            ->acceptedFileTypes([
                                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                                'application/vnd.ms-excel',
                                'text/csv',
                            ])
                            ->disk('local')
                            ->directory('imports')
                            ->required(),
                    ]),
            ])
            ->statePath('data');
    }

    public function import(): void
    {
        $data = $this->form->getState();

        try {
            $before = Good::count();

            // ✅ Fix path - pakai storage() helper langsung
            Excel::import(
                new GoodsImport(),
                storage_path('app/private/' . $data['file']) // ✅ tambah private/
            );

            $after    = Good::count();
            $imported = $after - $before;

            Notification::make()
                ->title('Import berhasil!')
                ->body("{$imported} barang berhasil diimport.")
                ->success()
                ->send();

            $this->form->fill();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Import gagal!')
                ->body('Error: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }
}
