<?php

namespace XtendLunar\Addons\PageBuilder\Components;

use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use XtendLunar\Addons\PageBuilder\Concerns\InteractsWithMediaSettings;
use XtendLunar\Addons\PageBuilder\Contracts\Widget;

abstract class AdvertisementWidget implements Widget
{
    use InteractsWithMediaSettings;

    protected static string $fieldsetLabel = 'Advertisement';

    public function schema(): array
    {
        return [
            Fieldset::make(static::$fieldsetLabel)
                ->schema([
                    TextInput::make('data.title')->columnSpan(2),
                    Textarea::make('data.description')->columnSpan(2),
                    ...$this->mediaSchema(),
                    TextInput::make('data.cta')->label('Call to action text')->columnSpan(1),
                    TextInput::make('data.route')->label('Url')->columnSpan(1),
                    TextInput::make('data.placement')->hidden(),
                ])->columns()
        ];
    }
}
