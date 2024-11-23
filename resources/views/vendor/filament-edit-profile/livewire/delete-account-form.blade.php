<div>
    @if (!auth()->user()->isAdmin())
        <x-filament-panels::form>
            {{ $this->form }}
        </x-filament-panels::form>

        <x-filament-actions::modals />
    @endif
</div>
