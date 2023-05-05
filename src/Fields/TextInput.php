<?php

namespace XtendLunar\Addons\PageBuilder\Fields;

use Filament\Forms\Components\TextInput as FilamentTextInput;
use XtendLunar\Addons\PageBuilder\Fields\Concerns\WithMultiLanguages;

class TextInput extends FilamentTextInput
{
    protected string $view = 'xtend-lunar-page-builder::components.fields.text-input';

    use WithMultiLanguages;
}
