<?php

namespace App\Filament\Guru\Resources\ProcurementRequests\Schemas;

use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProcurementRequestInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Pengajuan')
                    ->schema([
                        TextEntry::make('title')
                            ->label('Judul Pengajuan'),

                        TextEntry::make('status')
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

                        TextEntry::make('created_at')
                            ->label('Tanggal Pengajuan')
                            ->dateTime('d M Y, H:i'),

                        TextEntry::make('reason')
                            ->label('Alasan Pengajuan')
                            ->columnSpanFull(),
                    ])->columns(2),

                Section::make('Hasil Review')
                    ->schema([
                        TextEntry::make('reviewer.name')
                            ->label('Direview Oleh')
                            ->placeholder('-'),

                        TextEntry::make('reviewed_at')
                            ->label('Tanggal Review')
                            ->dateTime('d M Y, H:i')
                            ->placeholder('-')
                            ->default(null),

                        TextEntry::make('review_note')
                            ->label('Catatan Review')
                            ->placeholder('Belum ada catatan')
                            ->columnSpanFull(),
                    ])->columns(2),

                Section::make('Daftar Barang')
                    ->schema([
                        RepeatableEntry::make('items')
                            ->label('')
                            ->schema([
                                TextEntry::make('name')
                                    ->label('Nama Barang'),

                                TextEntry::make('specification')
                                    ->label('Spesifikasi')
                                    ->placeholder('-')
                                    ->columnSpanFull(),

                                TextEntry::make('quantity')
                                    ->label('Jumlah'),

                                TextEntry::make('unit')
                                    ->label('Satuan'),

                                TextEntry::make('estimated_price')
                                    ->label('Estimasi Harga')
                                    ->money('IDR')
                                    ->placeholder('-'),

                                TextEntry::make('note')
                                    ->label('Catatan')
                                    ->placeholder('-')
                                    ->columnSpanFull(),
                            ])
                            ->columns(2),
                    ]),
            ]);
    }
}
