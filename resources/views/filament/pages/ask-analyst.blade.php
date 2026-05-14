<x-filament-panels::page>
    <form wire:submit="analyze" class="space-y-6">
        {{ $this->form }}

        <div>
            <x-filament::button type="submit">
                Analisis Sekarang
            </x-filament::button>
        </div>
    </form>

    @if ($result)
        <x-filament::section heading="Hasil Analisis">
            <div class="whitespace-pre-line text-sm leading-6">
                {{ $result }}
            </div>
        </x-filament::section>
    @endif
</x-filament-panels::page>
