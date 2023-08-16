<?php

namespace XtendLunar\Addons\PageBuilder\Components\Form;

use Filament\Forms\Components\Fieldset;
use XtendLunar\Addons\PageBuilder\Components\FormWidget;
use XtendLunar\Addons\PageBuilder\Contracts\Widget;
use XtendLunar\Addons\PageBuilder\Fields\TextInput;

class Login extends FormWidget implements Widget
{
    public function schema(): array
    {
        return [
            Fieldset::make('Content')
                ->schema([
                    TextInput::make('data.heading')->translatable()->columnSpanFull(),
                    TextInput::make('data.sub_heading')->translatable()->columnSpanFull(),
                ]),
        ];
    }
}
