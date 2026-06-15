<?php

namespace App\Filament\Sarpras\Resources\ProcurementRequests\Tables;

use App\Filament\Sarpras\Resources\ProcurementRequests\ProcurementRequestResource;
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
            ->columns([
                TextColumn::make('title')
                    ->label('Judul Pengajuan')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('user.name')
                    ->label('Diajukan Oleh')
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
                    ->default('-')
                    ->sortable(),

                TextColumn::make('reviewed_at')
                    ->label('Tanggal Review')
                    ->dateTime('d M Y')
                    ->placeholder('-')
                    ->sortable(),

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
                EditAction::make()->label(''),

                // ✅ Action setujui/tolak untuk sarpras
                Action::make('setujui')
                    ->label('Setujui')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->visible(fn($record) => $record->status === 'Diajukan')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->update([
                            'status'      => 'Disetujui',
                            'reviewed_by' => auth()->id(),
                            'reviewed_at' => now(),
                        ]);
                    }),

                Action::make('tolak')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-mark')
                    ->color('danger')
                    ->visible(fn($record) => $record->status === 'Diajukan')
                    ->form([
                        \Filament\Forms\Components\Textarea::make('review_note')
                            ->label('Alasan Penolakan')
                            ->required()
                            ->rows(3),
                    ])
                    ->action(function ($record, array $data) {
                        $record->update([
                            'status'      => 'Ditolak',
                            'reviewed_by' => auth()->id(),
                            'reviewed_at' => now(),
                            'review_note' => $data['review_note'],
                        ]);
                    }),

                Action::make('realisasi')
                    ->label('Realisasi')
                    ->icon('heroicon-o-archive-box-arrow-down')
                    ->color('info')
                    ->visible(fn($record) => $record->status === 'Disetujui')
                    ->url(fn($record) => ProcurementRequestResource::getUrl('realisasi', ['record' => $record]))
                    ->openUrlInNewTab(false),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
