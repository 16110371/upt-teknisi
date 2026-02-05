<?php

namespace App\Filament\Pages;

use App\Models\Request;
use App\Models\Technician;
use Filament\Pages\Page;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use BackedEnum;
use Barryvdh\DomPDF\Facade\Pdf;

class Report extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationLabel = 'Laporan';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    // protected static ?string $maxContentWidth = 'full';

    public function getView(): string
    {
        return 'filament.pages.report';
    }

    /* ================= FORM FILTER ================= */
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('from')
                    ->label('Tanggal Mulai'),

                Forms\Components\DatePicker::make('to')
                    ->label('Tanggal Akhir'),

                Forms\Components\Select::make('status')
                    ->options([
                        'Pending' => 'Pending',
                        'Proses' => 'Proses',
                        'Selesai' => 'Selesai',
                    ])
                    ->placeholder('Semua Status'),

                Forms\Components\Select::make('technician_id')
                    ->label('Teknisi')
                    ->options(Technician::pluck('name', 'id'))
                    ->searchable()
                    ->placeholder('Semua Teknisi'),
            ])
            ->columns(4)
            ->statePath('filters');
    }

    /* ================= TABLE ================= */
    public function table(Table $table): Table
    {
        return $table
            ->query(
                Request::query()
                    ->with(['location', 'technician'])
                    ->when(
                        $this->filters['from'] ?? null,
                        fn($q, $date) => $q->whereDate('request_date', '>=', $date)
                    )
                    ->when(
                        $this->filters['to'] ?? null,
                        fn($q, $date) => $q->whereDate('request_date', '<=', $date)
                    )
                    ->when(
                        $this->filters['status'] ?? null,
                        fn($q, $status) => $q->where('status', $status)
                    )
                    ->when(
                        $this->filters['technician_id'] ?? null,
                        fn($q, $id) => $q->where('technician_id', $id)
                    )
                    ->latest('request_date')
            )
            ->columns([
                Tables\Columns\TextColumn::make('request_date')
                    ->label('Tanggal')
                    ->date('d M Y'),

                Tables\Columns\TextColumn::make('location.name')
                    ->label('Lokasi'),

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'Pending',
                        'info' => 'Proses',
                        'success' => 'Selesai',
                    ]),

                Tables\Columns\TextColumn::make('technician.name')
                    ->label('Teknisi')
                    ->default('-'),
            ])
            ->striped()
            ->paginated(false);
    }

    /* ================= PDF ================= */
    public function print()
    {
        $data = $this->getTableQuery()->get();

        $pdf = Pdf::loadView('pdf.laporan', [
            'reports' => $data,
        ]);

        return response()->streamDownload(
            fn() => print($pdf->output()),
            'laporan-upt.pdf'
        );
    }
}
