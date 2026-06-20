<?php

namespace App\Filament\Sarpras\Resources\Goods\Pages;

use App\Filament\Sarpras\Resources\Goods\GoodResource;
use App\Imports\GoodsImport;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Section;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Good;

class ListGoods extends ListRecords
{
    protected static string $resource = GoodResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('import')
                ->label('Import Excel')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('info')
                ->form([
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
                ])
                ->action(function (array $data) {
                    try {
                        $before = Good::count();

                        Excel::import(
                            new GoodsImport(),
                            storage_path('app/private/' . $data['file'])
                        );

                        $after    = Good::count();
                        $imported = $after - $before;

                        Notification::make()
                            ->title('Import berhasil!')
                            ->body("{$imported} barang berhasil diimport.")
                            ->success()
                            ->send();
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Import gagal!')
                            ->body('Error: ' . $e->getMessage())
                            ->danger()
                            ->send();
                    }
                })
                ->modalHeading('Import Data Barang')
                ->modalDescription(new \Illuminate\Support\HtmlString(
                    'Upload file Excel dengan format yang sesuai. ' .
                        '<a href="' . route('sarpras.goods.template') . '" target="_blank" ' .
                        'style="color:#2563eb;font-weight:600;">📥 Download Template</a>'
                ))
                ->modalSubmitActionLabel('Import'),
        ];
    }
}
