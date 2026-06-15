<?php

namespace App\Filament\Guru\Resources\ProcurementRequests\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ProcurementRequestsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->modifyQueryUsing(function ($query) {
                // ✅ Guru hanya lihat pengajuan milik sendiri
                return $query->where('user_id', auth()->id());
            })
            ->columns([
                TextColumn::make('title')
                    ->label('Judul Pengajuan')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('items_count')
                    ->label('Jumlah Item')
                    ->counts('items')
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Draft'     => 'gray',
                        'Diajukan'  => 'warning',
                        'Disetujui' => 'success',
                        'Ditolak'   => 'danger',
                        'Selesai'   => 'info',
                        default     => 'gray',
                    }),

                TextColumn::make('reviewer.name')
                    ->label('Direview Oleh')
                    ->default('-'),

                TextColumn::make('reviewed_at')
                    ->label('Tanggal Review')
                    ->dateTime('d M Y')
                    ->placeholder('-'),

                TextColumn::make('created_at')
                    ->label('Tanggal Pengajuan')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'Draft'     => 'Draft',
                        'Diajukan'  => 'Diajukan',
                        'Disetujui' => 'Disetujui',
                        'Ditolak'   => 'Ditolak',
                        'Selesai'   => 'Selesai',
                    ]),
            ])
            ->recordActions([
                ViewAction::make()->label(''),

                // ✅ Edit hanya kalau masih Draft atau Ditolak
                EditAction::make()
                    ->label('')
                    ->visible(fn($record) => in_array($record->status, ['Draft', 'Ditolak'])),

                // ✅ Tombol ajukan
                Action::make('ajukan')
                    ->label('Ajukan')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('primary')
                    ->visible(fn($record) => $record->status === 'Draft')
                    ->requiresConfirmation()
                    ->modalHeading('Ajukan Pengajuan')
                    ->modalDescription('Pengajuan akan dikirim ke tim Sarpras untuk direview.')
                    ->action(fn($record) => $record->update(['status' => 'Diajukan'])),

                // ✅ Hapus hanya kalau masih Draft
                \Filament\Actions\DeleteAction::make()
                    ->label('')
                    ->visible(fn($record) => $record->status === 'Draft'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
