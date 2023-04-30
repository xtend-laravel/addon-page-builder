<form wire:submit.prevent="submit">
    <div class="mb-4">
        {{ $this->form }}
    </div>

    <x-hub::button type="submit">
        {{ __('Save') }}
    </x-hub::button>
</form>

