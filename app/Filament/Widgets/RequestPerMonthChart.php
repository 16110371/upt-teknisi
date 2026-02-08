<?php

namespace App\Filament\Widgets;

use App\Models\Request;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class RequestPerMonthChart extends ChartWidget
{
    protected ?string $heading = 'Grafik Permintaan Per Bulan';
    protected static ?int $sort = 2;

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        $rows = Request::query()
            ->select(
                DB::raw('YEAR(request_date) as year'),
                DB::raw('MONTH(request_date) as month'),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        $labels = [];
        $data = [];

        foreach ($rows as $r) {
            $labels[] = $this->bulan($r->month) . ' ' . $r->year;
            $data[] = $r->total;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Permintaan',
                    'data' => $data,
                ],
            ],
            'labels' => $labels,
        ];
    }

    private function bulan(int $m): string
    {
        return [
            1 => 'Jan',
            2 => 'Feb',
            3 => 'Mar',
            4 => 'Apr',
            5 => 'Mei',
            6 => 'Jun',
            7 => 'Jul',
            8 => 'Agu',
            9 => 'Sep',
            10 => 'Okt',
            11 => 'Nov',
            12 => 'Des'
        ][$m] ?? (string)$m;
    }
}
