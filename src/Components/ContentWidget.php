<?php

namespace XtendLunar\Addons\PageBuilder\Components;

use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\TextInput;
use XtendLunar\Addons\PageBuilder\Contracts\Widget;

abstract class ContentWidget implements Widget
{
    public function schema(): array
    {
        return [
            Fieldset::make('Content')
                ->schema([
                    TextInput::make('data.heading'),
                    TextInput::make('data.sub_heading'),
                ]),
        ];
    }
}
