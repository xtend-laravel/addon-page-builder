<div class="flex-col space-y-4">
    <div class="flex items-center justify-between">
        <strong class="text-lg font-bold md:text-2xl">
            {{ __('Posts') }}
        </strong>

        <div class="text-right">
            <x-hub::button tag="a" href="{{ route('hub.content.posts.create') }}">
                {{ __('Create post') }}
            </x-hub::button>
        </div>
    </div>

    {{ $this->table }}
</div>