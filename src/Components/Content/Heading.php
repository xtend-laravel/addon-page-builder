<?php

namespace XtendLunar\Addons\PageBuilder\Components\Content;

use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\TextInput;
use XtendLunar\Addons\PageBuilder\Components\ContentWidget;
use XtendLunar\Addons\PageBuilder\Contracts\Widget;

class Heading extends ContentWidget implements Widget
{
    public function schema(): array
    {
        return [
            Fieldset::make('Content')
                ->schema([
                    TextInput::make('data.heading')->columnSpanFull(),
                    TextInput::make('data.sub_heading')->columnSpanFull(),
                ]),
        ];
    }
}
