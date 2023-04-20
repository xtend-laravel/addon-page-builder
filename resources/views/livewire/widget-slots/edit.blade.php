<form wire:submit.prevent="submit">
    {{ $this->form }}

    <div class="fixed bottom-0 right-0 z-40 border-t border-gray-100 bg-white/75 p-6 lg:left-auto"
         :class="{
        'lg:w-[calc(100vw_-_16rem)]': showExpandedMenu,
        'lg:w-[calc(100vw_-_5rem)]': !showExpandedMenu
    }">
        <form wire:submit.prevent="save">
            <div class="flex justify-end gap-6">
                <x-hub::button type="submit">
                    {{ __('Save') }}
                </x-hub::button>
            </div>
        </form>
    </div>
</form>

