<form wire:submit.prevent="submit">
    {{ $this->form }}

    <x-hub::button type="submit">
        {{ __('Save') }}
    </x-hub::button>
</form>

