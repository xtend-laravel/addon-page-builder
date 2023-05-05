<?php

namespace XtendLunar\Addons\PageBuilder\Fields;

use Filament\Forms\Components\Textarea as FilamentTextarea;
use XtendLunar\Addons\PageBuilder\Fields\Concerns\WithMultiLanguages;

class TextArea extends FilamentTextarea
{
    protected string $view = 'xtend-lunar-page-builder::components.fields.textarea';

    use WithMultiLanguages;
}
