<?php

namespace App\Filament\Resources\Infrastructures\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Actions\Action;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class UnitsRelationManager extends RelationManager
{
    protected static string $relationship = 'units';
    protected static ?string $title = 'Daftar Unit';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('code')
                    ->label('Kode Unit')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(50),

                Select::make('status')
                    ->label('Status')
                    ->options([
                        'good'             => '✅ Baik',
                        'broken'           => '🔧 Rusak',
                        'permanent_broken' => '❌ Rusak Permanen',
                    ])
                    ->default('good')
                    ->required(),

                Textarea::make('note')
                    ->label('Catatan')
                    ->nullable()
                    ->columnSpanFull(),

                Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true),
            ]);
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Unit')
                    ->schema([
                        TextEntry::make('code')
                            ->label('Kode Unit'),

                        TextEntry::make('status')
                            ->label('Status')
                            ->badge()
                            ->color(fn($state) => match ($state) {
                                'good'             => 'success',
                                'broken'           => 'warning',
                                'permanent_broken' => 'danger',
                                default            => 'gray',
                            })
                            ->formatStateUsing(fn($state) => match ($state) {
                                'good'             => '✅ Baik',
                                'broken'           => '🔧 Rusak',
                                'permanent_broken' => '❌ Rusak Permanen',
                                default            => $state,
                            }),

                        IconEntry::make('is_active')
                            ->label('Aktif')
                            ->boolean(),

                        TextEntry::make('note')
                            ->label('Catatan')
                            ->placeholder('-')
                            ->columnSpanFull(),

                        TextEntry::make('created_at')
                            ->label('Dibuat')
                            ->dateTime('d M Y, H:i'),

                        TextEntry::make('updated_at')
                            ->label('Diupdate')
                            ->dateTime('d M Y, H:i'),
                    ])->columns(2),

                Section::make('Riwayat Masalah')
                    ->schema([
                        \Filament\Infolists\Components\RepeatableEntry::make('logs')
                            ->label('')
                            ->schema([
                                TextEntry::make('type')
                                    ->label('Tipe')
                                    ->badge()
                                    ->color(fn($state) => match ($state) {
                                        'rusak'     => 'danger',
                                        'selesai'   => 'success',
                                        'permanent' => 'gray',
                                        'manual'    => 'info',
                                        default     => 'gray',
                                    }),

                                TextEntry::make('note')
                                    ->label('Keterangan')
                                    ->placeholder('-'),

                                TextEntry::make('request.requester_name')
                                    ->label('Dari Permintaan')
                                    ->placeholder('-'),

                                TextEntry::make('created_at')
                                    ->label('Tanggal')
                                    ->dateTime('d M Y, H:i'),
                            ])
                            ->columns(2),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('code')
            ->columns([
                TextColumn::make('code')
                    ->label('Kode Unit')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        'good'             => 'success',
                        'broken'           => 'warning',
                        'permanent_broken' => 'danger',
                        default            => 'gray',
                    })
                    ->formatStateUsing(fn($state) => match ($state) {
                        'good'             => '✅ Baik',
                        'broken'           => '🔧 Rusak',
                        'permanent_broken' => '❌ Rusak Permanen',
                        default            => $state,
                    }),

                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),

                TextColumn::make('note')
                    ->label('Catatan')
                    ->limit(30)
                    ->default('-'),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'good'             => '✅ Baik',
                        'broken'           => '🔧 Rusak',
                        'permanent_broken' => '❌ Rusak Permanen',
                    ]),

                SelectFilter::make('is_active')
                    ->label('Aktif')
                    ->options([
                        '1' => 'Aktif',
                        '0' => 'Tidak Aktif',
                    ]),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Tambah Unit'),
            ])
            ->recordActions([
                Action::make('qrcode')
                    ->label('QR')
                    ->icon('heroicon-o-qr-code')
                    ->color('info')
                    ->modalHeading(fn($record) => 'QR Code - ' . $record->code)
                    ->modalContent(fn($record) => new \Illuminate\Support\HtmlString(
                        '<div style="text-align:center; padding: 20px;">' .
                            QrCode::size(250)->generate(url('/unit/' . $record->code)) .
                            '<p style="margin-top: 12px; font-weight: bold;">' . $record->code . '</p>' .
                            '</div>'
                    ))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup'),
                ViewAction::make()->label(''),
                EditAction::make()->label(''),
                DeleteAction::make()->label(''),
                Action::make('print_qr')
                    ->label('QR')
                    ->icon('heroicon-o-qr-code')
                    ->color('success')
                    ->url(fn($record) => route('unit.qr.pdf', $record->id))
                    ->openUrlInNewTab(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
