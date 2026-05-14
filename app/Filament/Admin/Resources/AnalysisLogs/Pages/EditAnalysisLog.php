<?php

namespace App\Filament\Admin\Resources\AnalysisLogs\Pages;

use App\Filament\Admin\Resources\AnalysisLogs\AnalysisLogResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAnalysisLog extends EditRecord
{
    protected static string $resource = AnalysisLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
