<?php

namespace XtendLunar\Addons\PageBuilder\Components\Form;

use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Repeater;
use XtendLunar\Addons\PageBuilder\Components\FormWidget;
use XtendLunar\Addons\PageBuilder\Contracts\Widget;
use XtendLunar\Addons\PageBuilder\Fields\TextInput;

class Contact extends FormWidget implements Widget
{
    public function schema(): array
    {
        return [
            Fieldset::make('Content')
                ->schema([
                    TextInput::make('data.heading')->translatable()->columnSpanFull(),
                    Repeater::make('data.fields')
                        ->maxItems(6)
                        ->disableLabel()
                        ->defaultItems(1)
                        ->itemLabel(fn (\Closure $get, array $state, Repeater $component): ?string => 'Item #')
                        ->createItemButtonLabel('Add Form Field')
                        ->schema([
                            TextInput::make('field')->required(),
                            TextInput::make('placeholder')->translatable(),
                            TextInput::make('label'),
                            TextInput::make('size'),
                            TextInput::make('classes'),
                        ])->columnSpanFull(),
                ]),
        ];
    }
}
