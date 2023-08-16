<?php

namespace XtendLunar\Addons\PageBuilder\Components;

use Filament\Forms\Components\Fieldset;
use XtendLunar\Addons\PageBuilder\Contracts\Widget;
use XtendLunar\Addons\PageBuilder\Fields\TextInput;

abstract class FormWidget implements Widget
{
    public function schema(): array
    {
        return [
            Fieldset::make('Content')
                ->schema([
                    TextInput::make('data.heading')->translatable(),
                    TextInput::make('data.sub_heading')->translatable(),
                ]),
        ];
    }
}
