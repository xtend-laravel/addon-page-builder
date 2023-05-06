<?php

namespace XtendLunar\Addons\PageBuilder\Fields;

use Filament\Forms\Components\TextInput as FilamentTextInput;
use XtendLunar\Addons\PageBuilder\Fields\Concerns\WithLanguages;

class TextInput extends FilamentTextInput
{
    protected string $view = 'xtend-lunar-page-builder::components.fields.text-input';

    use WithLanguages;
}
