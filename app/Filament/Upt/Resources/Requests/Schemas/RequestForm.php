<?php

namespace App\Filament\Upt\Resources\Requests\Schemas;

use App\Models\GoodUnit;
use App\Models\Location;
use App\Models\Technician;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class RequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Pelapor')
                    ->schema([
                        TextInput::make('requester_name')
                            ->label('Nama Pelapor')
                            ->required()
                            ->maxLength(100),

                        TextInput::make('requester_contact')
                            ->label('Kontak')
                            ->nullable(),

                        DatePicker::make('request_date')
                            ->label('Tanggal')
                            ->required()
                            ->default(now()),

                        Select::make('priority')
                            ->label('Prioritas')
                            ->options([
                                'Rendah' => '🟢 Rendah',
                                'Sedang' => '🟡 Sedang',
                                'Tinggi' => '🔴 Tinggi',
                            ])
                            ->default('Rendah')
                            ->required(),
                    ])->columns(2),

                Section::make('Detail Kerusakan')
                    ->schema([
                        Select::make('location_id')
                            ->label('Lokasi')
                            ->options(Location::pluck('name', 'id'))
                            ->searchable()
                            ->required()
                            ->live(),

                        Select::make('broken_unit_ids')
                            ->label('Unit yang Rusak')
                            ->multiple()
                            ->options(function ($get, $record) {
                                $locationId = $get('location_id');
                                if (!$locationId) return [];

                                $query = GoodUnit::where('location_id', $locationId);

                                // ✅ Tampilkan unit good + unit yang sudah terpilih (broken)
                                if ($record) {
                                    $selectedIds = $record->brokenUnits()->pluck('unit_id')->toArray();
                                    $query->where(function ($q) use ($selectedIds) {
                                        $q->where('status', 'good')
                                            ->orWhereIn('id', $selectedIds);
                                    });
                                } else {
                                    $query->where('status', 'good');
                                }

                                return $query->with('good')
                                    ->get()
                                    ->mapWithKeys(fn($unit) => [
                                        $unit->id => "{$unit->code} - {$unit->good->name}"
                                    ]);
                            })
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set) {
                                $set('damaged_quantity', count($state ?? []));
                            })
                            ->hidden(fn($get) => !$get('location_id'))
                            ->dehydrated(false),

                        TextInput::make('damaged_quantity')
                            ->label('Jumlah Rusak')
                            ->numeric()
                            ->default(0)
                            ->readOnly()
                            ->dehydrated(true),

                        Textarea::make('description')
                            ->label('Deskripsi Masalah')
                            ->required()
                            ->rows(4)
                            ->columnSpanFull(),

                        FileUpload::make('photo')
                            ->label('Foto Kerusakan')
                            ->image()
                            ->disk('public')
                            ->directory('requests')
                            ->nullable(),
                    ])->columns(2),

                Section::make('Penanganan')
                    ->schema([
                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'Pending'          => 'Pending',
                                'Dikerjakan'       => 'Dikerjakan',
                                'Menunggu Part'    => 'Menunggu Part',
                                'Selesai'          => 'Selesai',
                                'Tidak Diperbaiki' => 'Tidak Diperbaiki',
                            ])
                            ->default('Pending')
                            ->required(),

                        Select::make('technicians')
                            ->label('Teknisi')
                            ->relationship('technicians', 'name')
                            ->multiple()
                            ->preload()
                            ->nullable(),

                        DateTimePicker::make('handled_at')
                            ->label('Waktu Ditangani')
                            ->nullable(),

                        DateTimePicker::make('completed_at')
                            ->label('Waktu Selesai')
                            ->nullable(),

                        // ✅ Pilih unit yang diperbaiki
                        Select::make('fixed_unit_ids')
                            ->label('Unit Diperbaiki')
                            ->multiple()
                            ->options(function ($get) {
                                $locationId = $get('location_id');
                                if (!$locationId) return [];

                                return GoodUnit::where('location_id', $locationId)
                                    ->where('status', 'broken') // ✅ hanya unit rusak
                                    ->with('good')
                                    ->get()
                                    ->mapWithKeys(fn($unit) => [
                                        $unit->id => "{$unit->code} - {$unit->good->name}"
                                    ]);
                            })
                            ->live()
                            ->afterStateUpdated(
                                fn($state, callable $set) =>
                                $set('fixed_quantity', count($state ?? []))
                            )
                            ->dehydrated(false),

                        TextInput::make('fixed_quantity')
                            ->label('Jumlah Diperbaiki')
                            ->numeric()
                            ->default(0)
                            ->readOnly()
                            ->dehydrated(true),

                        Select::make('permanent_unit_ids')
                            ->label('Unit Rusak Permanen')
                            ->multiple()
                            ->options(function ($get) {
                                $locationId = $get('location_id');
                                if (!$locationId) return [];

                                return GoodUnit::where('location_id', $locationId)
                                    ->where('status', 'broken') // ✅ hanya unit rusak
                                    ->with('good')
                                    ->get()
                                    ->mapWithKeys(fn($unit) => [
                                        $unit->id => "{$unit->code} - {$unit->good->name}"
                                    ]);
                            })
                            ->live()
                            ->afterStateUpdated(
                                fn($state, callable $set) =>
                                $set('permanent_quantity', count($state ?? []))
                            )
                            ->dehydrated(false),

                        TextInput::make('permanent_quantity')
                            ->label('Jumlah Rusak Permanen')
                            ->numeric()
                            ->default(0)
                            ->readOnly()
                            ->dehydrated(true),

                        Textarea::make('technician_note')
                            ->label('Catatan Teknisi')
                            ->nullable()
                            ->rows(3)
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }
}
