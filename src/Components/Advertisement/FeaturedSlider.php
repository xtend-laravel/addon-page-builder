<?php

namespace XtendLunar\Addons\PageBuilder\Components\Advertisement;

use Filament\Forms\Components\Repeater;
use XtendLunar\Addons\PageBuilder\Components\AdvertisementWidget;
use XtendLunar\Addons\PageBuilder\Contracts\Widget;

class FeaturedSlider extends AdvertisementWidget implements Widget
{
    public function schema(): array
    {
        return [
            Repeater::make('featured_slider')
                ->defaultItems(1)
                ->itemLabel(fn (\Closure $get, array $state, Repeater $component): ?string => 'Item #')
                ->createItemButtonLabel('Add Item')
                ->schema([
                    ...parent::schema(),
                ]),
        ];
    }
}
