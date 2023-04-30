<div class="flex-col space-y-4">
    <div class="flex items-center justify-between">
        <strong class="text-lg font-bold md:text-2xl">
            Widget slots
        </strong>

        <div class="text-right">
            <x-hub::button tag="a" href="{{ route('hub.page-builder.widget-slots.create') }}">
                Create widget slot
            </x-hub::button>
        </div>
    </div>

    @livewire('xtend-lunar-page-builder.widget-slots.table')
</div>
