<form wire:submit.prevent="submit">
    <div class="pb-20">
        {{ $this->form }}
    </div>
    <div class="fixed bottom-0 right-0 z-40 border-t border-gray-100 bg-white/75 p-6 lg:left-auto"
        :class="{
            'lg:w-[calc(100vw_-_15rem)]': showExpandedMenu,
            'lg:w-[calc(100vw_-_5rem)]': !showExpandedMenu
        }">
        <div class="flex justify-end gap-6">
            <x-hub::button type="submit">
                {{ __('Save Widgets') }}
            </x-hub::button>
        </div>
    </div>
</form>



