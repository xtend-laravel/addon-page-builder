<?php

namespace XtendLunar\Addons\PageBuilder\Components;

use Filament\Forms\Components\Fieldset;
use XtendLunar\Addons\PageBuilder\Concerns\InteractsWithMediaSettings;
use XtendLunar\Addons\PageBuilder\Contracts\Widget;
use XtendLunar\Addons\PageBuilder\Fields\TextArea;
use XtendLunar\Addons\PageBuilder\Fields\TextInput;

abstract class AdvertisementWidget implements Widget
{
    use InteractsWithMediaSettings;

    protected static string $fieldsetLabel = 'Advertisement';

    public function schema(): array
    {
        return [
            Fieldset::make(static::$fieldsetLabel)
                ->schema([
                    TextInput::make('data.title')
                        ->translatable()
                        ->columnSpan(2),
                    TextArea::make('data.description')
                        ->translatable()
                        ->columnSpan(2),
                    ...$this->mediaSchema(),
                    TextInput::make('data.cta')
                        ->translatable()
                        ->label('Call to action text')
                        ->columnSpan(1),
                    TextInput::make('data.route')
                        ->label('Url')
                        ->columnSpan(1),
                ])->columns()
        ];
    }
}
