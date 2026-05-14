<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\AnalysisLogResource\Pages;
use App\Models\AnalysisLog;
use BackedEnum;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;

class AnalysisLogResource extends Resource
{
    protected static ?string $model = AnalysisLog::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBar;

    protected static ?string $navigationLabel = 'Analysis Log';

    protected static ?string $pluralModelLabel = 'Analysis Logs';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Request')
                ->schema([
                    Select::make('symbol')
                        ->options([
                            'XAUUSD' => 'XAUUSD (Gold)',
                            'XAGUSD' => 'XAGUSD (Silver)',
                        ])
                        ->default('XAUUSD')
                        ->required(),

                    Textarea::make('prompt')
                        ->label('Pertanyaan Analisis')
                        ->rows(3)
                        ->required(),

                    TextInput::make('price_at_analysis')
                        ->label('Harga Saat Ini (USD)')
                        ->numeric()
                        ->prefix('$'),
                ]),

            Section::make('Hasil Analisis')
                ->schema([
                    Select::make('sentiment')
                        ->options([
                            'bullish' => 'Bullish',
                            'bearish' => 'Bearish',
                            'neutral' => 'Neutral',
                        ]),

                    Textarea::make('analysis_result')
                        ->label('Hasil')
                        ->rows(15)
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('symbol')
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('prompt')
                    ->label('Pertanyaan')
                    ->limit(60)
                    ->tooltip(fn (AnalysisLog $record): string => $record->prompt),

                Tables\Columns\TextColumn::make('sentiment')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'bullish' => 'success',
                        'bearish' => 'danger',
                        default => 'warning',
                    }),

                Tables\Columns\TextColumn::make('price_at_analysis')
                    ->label('Harga')
                    ->money('USD')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAnalysisLogs::route('/'),
            'create' => Pages\CreateAnalysisLog::route('/create'),
            'view' => Pages\ViewAnalysisLog::route('/{record}'),
        ];
    }
}