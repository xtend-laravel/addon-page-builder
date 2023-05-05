<div {{ $attributes->merge($getExtraAttributes())->class(['filament-forms-text-input-component flex items-center space-x-2 rtl:space-x-reverse group']) }}>
    @if (($prefixAction = $getPrefixAction()) && (! $prefixAction->isHidden()))
        {{ $prefixAction }}
    @endif

    @if ($icon = $getPrefixIcon())
        <x-dynamic-component :component="$icon" class="w-5 h-5" />
    @endif

    @if ($label = $getPrefixLabel())
        <span @class($affixLabelClasses)>
            {{ $label }}
        </span>
    @endif

    <div class="flex-1">
        <input
            x-data="{}"
            {{ $applyStateBindingModifiers('wire:model') }}="{{ $getStatePath() }}.{{ $locale }}"
            type="{{ $getType() }}"
            dusk="filament.forms.{{ $getStatePath() }}.{{ $locale }}"
            {!! ($autocapitalize = $getAutocapitalize()) ? "autocapitalize=\"{$autocapitalize}\"" : null !!}
            {!! ($autocomplete = $getAutocomplete()) ? "autocomplete=\"{$autocomplete}\"" : null !!}
            {!! $isAutofocused() ? 'autofocus' : null !!}
            {!! $isDisabled() ? 'disabled' : null !!}
            id="{{ $getId() }}"
            {!! ($inputMode = $getInputMode()) ? "inputmode=\"{$inputMode}\"" : null !!}
            {!! $datalistOptions ? "list=\"{$getId()}-list\"" : null !!}
            {!! ($placeholder = $getPlaceholder()) ? "placeholder=\"{$placeholder}\"" : null !!}
            {!! ($interval = $getStep()) ? "step=\"{$interval}\"" : null !!}
            @if (! $isConcealed())
                {!! filled($length = $getMaxLength()) ? "maxlength=\"{$length}\"" : null !!}
                {!! filled($value = $getMaxValue()) ? "max=\"{$value}\"" : null !!}
                {!! filled($length = $getMinLength()) ? "minlength=\"{$length}\"" : null !!}
                {!! filled($value = $getMinValue()) ? "min=\"{$value}\"" : null !!}
                {!! $isRequired() ? 'required' : null !!}
            @endif
            {{ $getExtraAlpineAttributeBag() }}
            {{ $getExtraInputAttributeBag()->class([
                'filament-forms-input block w-full transition duration-75 rounded-lg shadow-sm outline-none focus:ring-1 focus:ring-inset disabled:opacity-70',
                'dark:bg-gray-700 dark:text-white' => config('forms.dark_mode'),
            ]) }}
            x-bind:class="{
                'border-gray-300 focus:border-primary-500 focus:ring-primary-500': ! (@js($getStatePath()) in $wire.__instance.serverMemo.errors),
                'dark:border-gray-600 dark:focus:border-primary-500': ! (@js($getStatePath()) in $wire.__instance.serverMemo.errors) && @js(config('forms.dark_mode')),
                'border-danger-600 ring-danger-600 focus:border-danger-500 focus:ring-danger-500': (@js($getStatePath()) in $wire.__instance.serverMemo.errors),
                'dark:border-danger-400 dark:ring-danger-400 dark:focus:border-danger-500 dark:focus:ring-danger-500': (@js($getStatePath()) in $wire.__instance.serverMemo.errors) && @js(config('forms.dark_mode')),
            }"
        />
    </div>

    @if ($label = $getSuffixLabel())
        <span @class($affixLabelClasses)>
            {{ $label }}
        </span>
    @endif

    @if ($icon = $getSuffixIcon())
        <x-dynamic-component :component="$icon" class="w-5 h-5" />
    @endif

    @if (($suffixAction = $getSuffixAction()) && (! $suffixAction->isHidden()))
        {{ $suffixAction }}
    @endif
</div>
