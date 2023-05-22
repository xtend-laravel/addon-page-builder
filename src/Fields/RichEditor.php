<?php

namespace XtendLunar\Addons\PageBuilder\Fields;

use Filament\Forms\Components\RichEditor as FilamentRichEditor;
use XtendLunar\Addons\PageBuilder\Fields\Concerns\WithLanguages;

class RichEditor extends FilamentRichEditor
{
    protected string $view = 'xtend-lunar-page-builder::components.fields.rich-editor';

    use WithLanguages;
}
