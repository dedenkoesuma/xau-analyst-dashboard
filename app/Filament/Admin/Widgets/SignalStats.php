<?php

namespace App\Filament\Admin\Widgets;

use App\Models\AnalysisLog;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SignalStats extends BaseWidget
{
    protected function getStats(): array
    {
        $total = AnalysisLog::count();
        $bullish = AnalysisLog::where('sentiment', 'bullish')->count();
        $bearish = AnalysisLog::where('sentiment', 'bearish')->count();
        $neutral = AnalysisLog::where('sentiment', 'neutral')->count();

        return [
            Stat::make('Total Analisis', $total)
                ->description('Semua log analisis')
                ->color('primary'),

            Stat::make('Bullish', $bullish)
                ->description('Sinyal bullish')
                ->color('success'),

            Stat::make('Bearish', $bearish)
                ->description('Sinyal bearish')
                ->color('danger'),

            Stat::make('Neutral', $neutral)
                ->description('Sinyal netral')
                ->color('warning'),
        ];
    }
}