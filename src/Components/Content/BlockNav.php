<?php

namespace XtendLunar\Addons\PageBuilder\Components\Content;

use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use XtendLunar\Addons\PageBuilder\Components\ContentWidget;
use XtendLunar\Addons\PageBuilder\Contracts\Widget;

class BlockNav extends ContentWidget implements Widget
{
    public function schema(): array
    {
        return [
            Repeater::make('block_nav')
                ->maxItems(6)
                ->disableLabel()
                ->defaultItems(1)
                ->itemLabel(fn (\Closure $get, array $state, Repeater $component): ?string => 'Item #')
                ->createItemButtonLabel('Add Item')
                ->schema([
                    TextInput::make('name')->required(),
                    TextInput::make('link'),
                ]),
        ];
    }
}
