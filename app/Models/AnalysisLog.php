<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'symbol',
    'prompt',
    'analysis_result',
    'provider',
    'model',
    'price_at_analysis',
    'sentiment',
])]
class AnalysisLog extends Model
{
    protected function casts(): array
    {
        return [
            'price_at_analysis' => 'decimal:2',
        ];
    }
}