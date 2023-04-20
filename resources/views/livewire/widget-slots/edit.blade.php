<form wire:submit.prevent="submit">
    <!-- where it should be?? -->
    <link href="https://unpkg.com/filepond@^4/dist/filepond.css" rel="stylesheet"/>

    {{ $this->form }}

    <x-hub::button type="submit">
        {{ __('Save') }}
    </x-hub::button>
</form>

