<div class="flex-col space-y-4">
    <div class="flex items-center justify-between">
        <strong class="text-lg font-bold md:text-2xl">
            {{ __('Pages') }}
        </strong>

        <div class="text-right">
            <x-hub::button tag="a" href="{{ route('hub.page-builder.widget-slots.create') }}">
                {{ __('Create page') }}
            </x-hub::button>
        </div>
    </div>

    @livewire('xtend-lunar-page-builder.widget-slots.table')
</div>
