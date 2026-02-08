<?php

namespace App\Filament\Widgets;

use App\Models\Request;
use App\Models\Category;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class RequestPerMonthByCategoryChart extends ChartWidget
{
    protected ?string $heading = 'Permintaan per Bulan per Kategori';
    protected static ?int $sort = 3;

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        $year = now()->year;

        // label bulan
        $labels = collect(range(1, 12))
            ->map(fn($m) => Carbon::create()->month($m)->format('M'))
            ->toArray();

        $categories = Category::pluck('name', 'id');

        // warna untuk tiap kategori (akan diputar jika kategori banyak)
        $colors = [
            '#3b82f6', // biru
            '#ef4444', // merah
            '#22c55e', // hijau
            '#f59e0b', // kuning
            '#8b5cf6', // ungu
            '#06b6d4', // cyan
            '#f97316', // orange
            '#ec4899', // pink
        ];

        $datasets = [];
        $colorIndex = 0;

        foreach ($categories as $catId => $catName) {
            $data = [];

            for ($month = 1; $month <= 12; $month++) {
                $count = Request::query()
                    ->whereYear('request_date', $year)
                    ->whereMonth('request_date', $month)
                    ->where('category_id', $catId)
                    ->count();

                $data[] = $count;
            }

            $color = $colors[$colorIndex % count($colors)];
            $colorIndex++;

            $datasets[] = [
                'label' => $catName,
                'data' => $data,
                'borderColor' => $color,
                'backgroundColor' => $color,
                'fill' => false,
                'tension' => 0.3,
            ];
        }

        return [
            'datasets' => $datasets,
            'labels' => $labels,
        ];
    }
}
