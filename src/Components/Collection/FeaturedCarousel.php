<?php

namespace XtendLunar\Addons\PageBuilder\Components\Collection;

use Filament\Forms\Components\TextInput;
use XtendLunar\Addons\PageBuilder\Components\CollectionWidget;
use XtendLunar\Addons\PageBuilder\Contracts\Widget;

class FeaturedCarousel extends CollectionWidget implements Widget
{
    public function schema(): array
    {
        return array_merge(parent::schema(), [
            TextInput::make('params.limit')->columnSpan(2),
        ]);
    }
}
