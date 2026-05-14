<?php

namespace App\Filament\Admin\Pages;

use App\Ai\Agents\MarketAnalystAgent;
use BackedEnum;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;

class AskAnalyst extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-sparkles';

    protected static ?string $navigationLabel = 'Tanya AI Analyst';

    protected string $view = 'filament.pages.ask-analyst';

    public ?array $data = [];

    public string $result = '';

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('price')
                    ->label('Harga XAUUSD Saat Ini (USD)')
                    ->numeric()
                    ->prefix('$')
                    ->placeholder('Contoh: 3250.50'),

                Textarea::make('prompt')
                    ->label('Pertanyaan Analisis')
                    ->placeholder('Contoh: Bagaimana prospek emas hari ini berdasarkan level fibo?')
                    ->rows(3)
                    ->required(),
            ])
            ->statePath('data');
    }

    public function analyze(): void
    {
        $this->form->validate();

        $result = app(MarketAnalystAgent::class)->analyze(
            prompt: $this->data['prompt'],
            currentPrice: filled($this->data['price'] ?? null)
                ? (float) $this->data['price']
                : null,
        );

        $this->result = is_string($result) ? $result : $result->analysis_result;

        Notification::make()
            ->title('Analisis selesai')
            ->success()
            ->send();
    }
}