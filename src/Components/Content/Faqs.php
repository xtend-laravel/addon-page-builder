<?php

namespace XtendLunar\Addons\PageBuilder\Components\Content;

use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Repeater;
use XtendLunar\Addons\PageBuilder\Components\ContentWidget;
use XtendLunar\Addons\PageBuilder\Contracts\Widget;
use XtendLunar\Addons\PageBuilder\Fields\TextInput;

class Faqs extends ContentWidget implements Widget
{
    public function schema(): array
    {
        return [
            Repeater::make('data')
                ->maxItems(6)
                ->disableLabel()
                ->defaultItems(1)
                ->itemLabel(fn (\Closure $get, array $state, Repeater $component): ?string => 'Item #')
                ->createItemButtonLabel('Add Item')
                ->schema([
                    TextInput::make('question')->translatable()->required(),
                    TextInput::make('answer')->translatable()->required(),
                ])->columnSpanFull(),
        ];
    }
}
