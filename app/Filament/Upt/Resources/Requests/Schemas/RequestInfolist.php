<?php

namespace App\Filament\Upt\Resources\Requests\Schemas;

use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class RequestInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Pelapor')
                    ->schema([
                        TextEntry::make('requester_name')
                            ->label('Nama Pelapor'),

                        TextEntry::make('requester_contact')
                            ->label('Kontak')
                            ->placeholder('-'),

                        TextEntry::make('request_date')
                            ->label('Tanggal')
                            ->date('d M Y'),

                        TextEntry::make('priority')
                            ->label('Prioritas')
                            ->badge()
                            ->color(fn($state) => match ($state) {
                                'Tinggi' => 'danger',
                                'Sedang' => 'warning',
                                'Rendah' => 'success',
                                default  => 'gray',
                            }),
                    ])->columns(2),

                Section::make('Detail Kerusakan')
                    ->schema([
                        TextEntry::make('location.name')
                            ->label('Lokasi'),

                        TextEntry::make('damaged_quantity')
                            ->label('Jumlah Rusak'),

                        TextEntry::make('description')
                            ->label('Deskripsi')
                            ->columnSpanFull(),

                        ImageEntry::make('photo')
                            ->label('Foto')
                            ->disk('public')
                            ->placeholder('-')
                            ->columnSpanFull(),
                    ])->columns(2),

                Section::make('Penanganan')
                    ->schema([
                        TextEntry::make('status')
                            ->label('Status')
                            ->badge()
                            ->color(fn($state) => match ($state) {
                                'Pending'          => 'warning',
                                'Dikerjakan'       => 'info',
                                'Menunggu Part'    => 'gray',
                                'Selesai'          => 'success',
                                'Tidak Diperbaiki' => 'danger',
                                default            => 'gray',
                            }),

                        TextEntry::make('technicians.name')
                            ->label('Teknisi')
                            ->badge()
                            ->separator(',')
                            ->placeholder('-'),

                        TextEntry::make('handled_at')
                            ->label('Waktu Ditangani')
                            ->dateTime('d M Y, H:i')
                            ->placeholder('-'),

                        TextEntry::make('completed_at')
                            ->label('Waktu Selesai')
                            ->dateTime('d M Y, H:i')
                            ->placeholder('-'),

                        TextEntry::make('fixed_quantity')
                            ->label('Jumlah Diperbaiki'),

                        TextEntry::make('permanent_quantity')
                            ->label('Jumlah Rusak Permanen'),

                        TextEntry::make('technician_note')
                            ->label('Catatan Teknisi')
                            ->placeholder('-')
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }
}
