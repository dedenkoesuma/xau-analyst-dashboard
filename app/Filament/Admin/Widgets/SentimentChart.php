<?php

namespace App\Filament\Admin\Widgets;

use App\Models\AnalysisLog;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class SentimentChart extends ChartWidget
{
    public function getHeading(): string
    {
        return 'Tren Sentimen 7 Hari';
    }

    protected function getData(): array
    {
        $days = collect(range(6, 0))->map(fn ($i) => Carbon::now()->subDays($i));

        $labels = $days->map(fn ($day) => $day->format('d M'))->toArray();

        $bullish = $days->map(fn ($day) => AnalysisLog::whereDate('created_at', $day->toDateString())
            ->where('sentiment', 'bullish')
            ->count())->toArray();

        $bearish = $days->map(fn ($day) => AnalysisLog::whereDate('created_at', $day->toDateString())
            ->where('sentiment', 'bearish')
            ->count())->toArray();

        $neutral = $days->map(fn ($day) => AnalysisLog::whereDate('created_at', $day->toDateString())
            ->where('sentiment', 'neutral')
            ->count())->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Bullish',
                    'data' => $bullish,
                    'borderColor' => '#16a34a',
                    'backgroundColor' => 'rgba(22, 163, 74, 0.15)',
                ],
                [
                    'label' => 'Bearish',
                    'data' => $bearish,
                    'borderColor' => '#dc2626',
                    'backgroundColor' => 'rgba(220, 38, 38, 0.15)',
                ],
                [
                    'label' => 'Neutral',
                    'data' => $neutral,
                    'borderColor' => '#f59e0b',
                    'backgroundColor' => 'rgba(245, 158, 11, 0.15)',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}