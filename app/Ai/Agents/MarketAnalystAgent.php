<?php

namespace App\Ai\Agents;

use App\Models\AnalysisLog;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Promptable;
use Stringable;

class MarketAnalystAgent implements Agent
{
    use Promptable;

    public function instructions(): Stringable|string
    {
        return <<<'PROMPT'
Anda adalah analis trading profesional untuk XAUUSD.

Aturan wajib:
1. Selalu gunakan harga XAUUSD dan tanggal yang diberikan user sebagai acuan utama.
2. Jangan gunakan data historis lama jika tidak diminta.
3. Jangan menyebut level harga 2000-an atau 3000-an jika harga acuan user ada di level 4000-an.
4. Semua support, resistance, Fibonacci, entry, stop loss, dan take profit harus logis terhadap harga acuan saat ini.
5. Jawab dalam bahasa Indonesia yang ringkas, jelas, profesional, dan actionable.
6. Selalu sebutkan sentimen pasar: bullish, bearish, atau neutral.
PROMPT;
    }

    public function analyze(
        string $prompt,
        ?float $currentPrice = null,
        string $symbol = 'XAUUSD',
        string $provider = 'gemini',
        ?string $model = 'gemini-2.5-flash',
    ): AnalysisLog {
        $today = Carbon::now()->format('Y-m-d H:i:s');
        $priceText = $currentPrice !== null
            ? number_format($currentPrice, 2, '.', '')
            : 'tidak diberikan';

        $finalPrompt = <<<PROMPT
Tanggal analisis: {$today}
Symbol: {$symbol}
Harga saat ini: {$priceText}

Instruksi penting:
- Gunakan harga saat ini di atas sebagai acuan utama.
- Jika harga saat ini berada di kisaran 4000-an, jangan gunakan level 2000-an.
- Semua level teknikal harus relevan dengan harga saat ini.
- Jika data harga tidak diberikan, nyatakan bahwa analisis hanya bersifat umum.

Permintaan user:
{$prompt}

Format jawaban:
1. Sentimen pasar
2. Support dan resistance utama
3. Level Fibonacci relevan
4. Skenario entry, stop loss, take profit
5. Kesimpulan singkat actionable
PROMPT;

        $result = trim((string) $this->prompt($finalPrompt));

        return AnalysisLog::query()->create([
            'symbol' => $symbol,
            'prompt' => $prompt,
            'analysis_result' => $result,
            'provider' => $provider,
            'model' => $model,
            'price_at_analysis' => $currentPrice,
            'sentiment' => $this->detectSentiment($result),
        ]);
    }

    private function detectSentiment(string $text): string
    {
        $text = Str::lower($text);

        $bullishWords = ['bullish', 'buy', 'naik', 'long', 'uptrend', 'menguat'];
        $bearishWords = ['bearish', 'sell', 'turun', 'short', 'downtrend', 'melemah'];

        $bullishScore = 0;
        $bearishScore = 0;

        foreach ($bullishWords as $word) {
            $bullishScore += substr_count($text, $word);
        }

        foreach ($bearishWords as $word) {
            $bearishScore += substr_count($text, $word);
        }

        return match (true) {
            $bullishScore > $bearishScore => 'bullish',
            $bearishScore > $bullishScore => 'bearish',
            default => 'neutral',
        };
    }
}