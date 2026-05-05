<?php

namespace App\Filament\Pages;

use App\Models\Request;
use App\Models\Location;
use App\Models\Technician;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Builder;
use BackedEnum;
use Filament\Support\Icons\Heroicon;
use Filament\Actions\Action;
use UnitEnum;

class Report extends Page implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;

    protected string $view = 'filament.pages.report';

    protected static string|UnitEnum|null $navigationGroup = 'Data';
    protected static ?string $navigationLabel = 'Laporan';
    protected static ?string $title = 'Laporan';
    protected static ?int $navigationSort = 2;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPrinter;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Request::query()
                    ->with(['location', 'category', 'technicians', 'infrastructure'])
                    ->latest('request_date')
            )
            ->columns([
                Tables\Columns\TextColumn::make('request_date')
                    ->label('Tanggal')
                    ->formatStateUsing(
                        fn($state) => \Carbon\Carbon::parse($state)->translatedFormat('d M Y')
                    )
                    ->searchable(),

                Tables\Columns\TextColumn::make('requester_name')
                    ->label('Peminta')
                    ->searchable(),

                Tables\Columns\TextColumn::make('location.name')
                    ->label('Lokasi')
                    ->searchable(),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori')
                    ->searchable(),

                Tables\Columns\TextColumn::make('infrastructure.name')
                    ->label('Item')
                    ->default('-'),

                Tables\Columns\TextColumn::make('damaged_quantity')
                    ->label('Rusak')
                    ->default('-'),

                Tables\Columns\TextColumn::make('fixed_quantity')
                    ->label('Diperbaiki')
                    ->default('-'),

                Tables\Columns\TextColumn::make('permanent_quantity')
                    ->label('Permanen')
                    ->default('-'),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        'Pending'          => 'warning',
                        'Dikerjakan'       => 'info',
                        'Menunggu Part'    => 'gray',
                        'Selesai'          => 'success',
                        'Tidak Diperbaiki' => 'danger',
                        default            => 'gray',
                    }),

                Tables\Columns\TextColumn::make('technicians.name')
                    ->label('Teknisi')
                    ->badge()
                    ->separator(',')
                    ->default('-'),
            ])
            ->filters([
                // ✅ Filter tanggal
                Filter::make('request_date')
                    ->label('Tanggal')
                    ->schema([
                        DatePicker::make('from')->label('Dari'),
                        DatePicker::make('until')->label('Sampai'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['from'], fn($q, $date) => $q->whereDate('request_date', '>=', $date))
                            ->when($data['until'], fn($q, $date) => $q->whereDate('request_date', '<=', $date));
                    }),

                // ✅ Filter status - fix nilai
                Filter::make('status')
                    ->form([
                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'Pending'          => 'Pending',
                                'Dikerjakan'       => 'Dikerjakan',
                                'Menunggu Part'    => 'Menunggu Part',
                                'Selesai'          => 'Selesai',
                                'Tidak Diperbaiki' => 'Tidak Diperbaiki',
                            ])
                    ])
                    ->query(
                        fn(Builder $query, array $data) =>
                        $query->when($data['status'], fn($q, $v) => $q->where('status', $v))
                    ),

                // ✅ Filter lokasi
                Filter::make('location')
                    ->form([
                        Select::make('location_id')
                            ->label('Lokasi')
                            ->options(Location::pluck('name', 'id'))
                            ->searchable()
                    ])
                    ->query(
                        fn(Builder $query, array $data) =>
                        $query->when($data['location_id'], fn($q, $v) => $q->where('location_id', $v))
                    ),

                // ✅ Filter teknisi - fix pakai relasi
                Filter::make('technician')
                    ->form([
                        Select::make('technician_id')
                            ->label('Teknisi')
                            ->options(Technician::pluck('name', 'id'))
                            ->searchable()
                    ])
                    ->query(
                        fn(Builder $query, array $data) =>
                        $query->when(
                            $data['technician_id'],
                            fn($q, $v) => $q->whereHas('technicians', fn($q) => $q->where('technicians.id', $v))
                        )
                    ),
            ])
            ->headerActions([
                Action::make('pdf')
                    ->label('Cetak PDF')
                    ->icon('heroicon-o-printer')
                    ->action('print')
            ])
            ->defaultSort('request_date', 'desc')
            ->paginated(10);
    }

    public function print()
    {
        $data = $this->getFilteredTableQuery()
            ->with(['location', 'category', 'technicians', 'infrastructure'])
            ->get();

        $pdf = Pdf::loadView('pdf.laporan', [
            'reports'    => $data,
            'printed_at' => now(),
        ])->setPaper('a4', 'landscape');

        return response()->streamDownload(
            fn() => print($pdf->output()),
            'laporan-permintaan-' . now()->format('Ymd') . '.pdf'
        );
    }
}
