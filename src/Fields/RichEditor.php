<?php

namespace XtendLunar\Addons\PageBuilder\Fields;

use Filament\Forms\Components\RichEditor as FilamentRichEditor;
use XtendLunar\Addons\PageBuilder\Fields\Concerns\WithLanguages;

class RichEditor extends FilamentRichEditor
{
    use WithLanguages;
}
