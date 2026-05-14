<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('analysis_logs', function (Blueprint $table) {
            $table->id();
            $table->string('symbol')->default('XAUUSD');
            $table->text('prompt');
            $table->longText('analysis_result');
            $table->string('provider')->default('gemini');
            $table->string('model')->nullable();
            $table->decimal('price_at_analysis', 10, 2)->nullable();
            $table->enum('sentiment', ['bullish', 'bearish', 'neutral'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('analysis_logs');
    }
};
