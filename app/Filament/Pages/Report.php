<?php

namespace App\Filament\Pages;

use App\Models\Request;
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

class Report extends Page implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;

    protected string $view = 'filament.pages.report';

    protected static ?string $navigationLabel = 'Laporan';

    protected static ?string $title = 'Laporan';

    protected static ?string $pluralModelLabel = 'Laporan';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPrinter;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Request::query()
                    ->with(['location', 'category', 'technician'])
                    ->latest('request_date')
            )

            ->columns([
                Tables\Columns\TextColumn::make('request_date')
                    ->label('Tanggal')
                    ->formatStateUsing(
                        fn($state) =>
                        \Carbon\Carbon::parse($state)->translatedFormat('d M Y')
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

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        'Pending' => 'warning',
                        'Proses' => 'info',
                        'Selesai' => 'success',
                        default => 'gray',
                    })
                    ->searchable(),

                Tables\Columns\TextColumn::make('technician.name')
                    ->label('Teknisi')
                    ->default('-')
                    ->searchable(),
            ])

            ->filters([

                // ✅ FILTER RENTANG TANGGAL
                Filter::make('request_date')
                    ->label('Tanggal')
                    ->schema([
                        DatePicker::make('from'),
                        DatePicker::make('until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn($q, $date) => $q->whereDate('request_date', '>=', $date)
                            )
                            ->when(
                                $data['until'],
                                fn($q, $date) => $q->whereDate('request_date', '<=', $date)
                            );
                    }),

                // ✅ FILTER STATUS
                Filter::make('status')
                    ->form([
                        Select::make('status')
                            ->options([
                                'Pending' => 'Pending',
                                'Proses' => 'Proses',
                                'Selesai' => 'Selesai',
                            ])
                    ])
                    ->query(
                        fn(Builder $query, array $data) =>
                        $query->when(
                            $data['status'],
                            fn($q, $v) => $q->where('status', $v)
                        )
                    ),

                // ✅ FILTER TEKNISI
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
                            fn($q, $v) => $q->where('technician_id', $v)
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
        $data = $this->getFilteredTableQuery()->get();

        $pdf = Pdf::loadView('pdf.laporan', [
            'reports' => $data,
            'printed_at' => now(),
        ]);

        return response()->streamDownload(
            fn() => print($pdf->output()),
            'laporan.pdf'
        );
    }
}
