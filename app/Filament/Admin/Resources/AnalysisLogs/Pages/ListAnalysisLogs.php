<?php

namespace App\Filament\Admin\Resources\AnalysisLogs\Pages;

use App\Filament\Admin\Resources\AnalysisLogs\AnalysisLogResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAnalysisLogs extends ListRecords
{
    protected static string $resource = AnalysisLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
