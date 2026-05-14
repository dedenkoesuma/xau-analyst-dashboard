<?php

namespace App\Filament\Admin\Pages;

use App\Filament\Admin\Widgets\SentimentChart;
use App\Filament\Admin\Widgets\SignalStats;
use BackedEnum;
use Filament\Pages\Page;

class MarketDashboard extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationLabel = 'Market Dashboard';

    protected ?string $heading = 'Market Dashboard';

    protected string $view = 'filament.admin.pages.market-dashboard';

    protected function getHeaderWidgets(): array
    {
        return [
            SignalStats::class,
            SentimentChart::class,
        ];
    }
}