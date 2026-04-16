<?php

namespace App\Filament\Widgets;

use App\Models\Request;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class RequestStats extends StatsOverviewWidget
{
    protected static ?int $sort = 1;
    protected function getStats(): array

    {
        return [
            Stat::make('Total Permintaan', Request::count())
                ->icon('heroicon-o-clipboard-document-list')
                ->color('primary')
                ->description('Semua data'),

            Stat::make('Pending', Request::where('status', 'Pending')->count())
                ->icon('heroicon-o-clock')
                ->color('warning')
                ->description('Menunggu'),

            Stat::make('Proses', Request::where('status', 'Dikerjakan')->count())
                ->icon('heroicon-o-cog-6-tooth')
                ->color('info')
                ->description('Dikerjakan'),

            Stat::make('Selesai', Request::where('status', 'Selesai')->count())
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->description('Selesai'),
        ];
    }
}
