<?php

namespace App\Filament\Resources\Procurements\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use App\Models\Procurement;
use Filament\Actions\Action;
use Filament\Actions\BulkAction as ActionsBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Database\Eloquent\Collection;

class ProcurementsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('requested_at')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('item_name')
                    ->label('Nama Barang')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('purchase_url')
                    ->label('Link Belanja')
                    ->icon('heroicon-m-arrow-top-right-on-square')
                    ->color('info')
                    // Kita tampilkan teks "Buka Link" daripada URL yang panjang
                    ->formatStateUsing(fn() => 'Buka Link')
                    // Menjadikan kolom ini bisa diklik
                    ->url(fn($state) => $state, shouldOpenInNewTab: true)
                    // Hanya muncul jika data link ada (tidak kosong)
                    ->visible(fn($state) => filled($state)),
                TextColumn::make('quantity')
                    ->label('Jml')
                    ->alignCenter(),
                TextColumn::make('total_price')
                    ->label('Total Estimasi')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'pending' => 'Pending',
                        'approved' => 'Disetujui',
                        'purchased' => 'Selesai',
                        default => $state,
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'purchased' => 'primary',
                        default => 'gray',
                    }),
                // SelectColumn::make('status')
                //     ->options([
                //         'pending' => 'Pending',
                //         'approved' => 'Disetujui',
                //         'purchased' => 'Selesai',
                //     ]),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Disetujui',
                        'purchased' => 'Selesai',
                    ]),
            ])
            ->recordActions([
                ViewAction::make()
                    ->label(''),
                Action::make('visit_link')
                    ->label('Link')
                    ->icon('heroicon-m-shopping-bag')
                    ->color('warning')
                    ->url(fn($record) => $record->purchase_url)
                    ->openUrlInNewTab()
                    // Tombol hanya muncul jika ada linknya
                    ->visible(fn($record) => filled($record->purchase_url)),
                Action::make('print')
                    ->label('Cetak PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('success')
                    ->url(fn(Procurement $record): string => route('procurement.print', $record))
                    ->openUrlInNewTab(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ActionsBulkAction::make('print_bulk')
                        ->label('Cetak Terpilih (PDF)')
                        ->icon('heroicon-o-printer')
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading('Cetak Laporan Kolektif')
                        ->modalDescription('Apakah Anda yakin ingin mencetak semua item yang dipilih dalam satu laporan?')
                        ->action(function (\Illuminate\Database\Eloquent\Collection $records, \Filament\Actions\BulkAction $action) {
                            // 1. Ambil semua ID yang dipilih
                            $ids = $records->pluck('id')->implode(',');

                            // 2. Generate URL ke route cetak
                            $url = route('procurement.print_bulk', ['ids' => $ids]);

                            // 3. Panggil fungsi JS via getLivewire() untuk membuka tab baru dengan aman
                            $action->getLivewire()->js("window.open('{$url}', '_blank')");
                        }),
                ]),
            ]);
    }
}
