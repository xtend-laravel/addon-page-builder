<div class="flex-col space-y-4">
    <div class="flex items-center justify-between">
        <strong class="text-lg font-bold md:text-2xl">
            {{ __('Categories') }}
        </strong>

        <div class="text-right">
            <x-hub::button tag="a" href="{{ route('hub.content.categories.create') }}">
                {{ __('Create category') }}
            </x-hub::button>
        </div>
    </div>

    {{ $this->table }}
</div>