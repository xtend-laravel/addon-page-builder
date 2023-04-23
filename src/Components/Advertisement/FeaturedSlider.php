<?php

namespace XtendLunar\Addons\PageBuilder\Components\Advertisement;

use Filament\Forms\Components\Repeater;
use XtendLunar\Addons\PageBuilder\Components\AdvertisementWidget;
use XtendLunar\Addons\PageBuilder\Contracts\Widget;

class FeaturedSlider extends AdvertisementWidget implements Widget
{
    protected static string $fieldsetLabel = 'Slides';

    public function schema(): array
    {
        return [
            Repeater::make('data.slides')
                ->collapsed()
                ->disableLabel()
                ->defaultItems(1)
                ->itemLabel('Item #')
                ->createItemButtonLabel('Add Item')
                ->schema([
                    ...parent::schema(),
                ]),
        ];
    }
}
