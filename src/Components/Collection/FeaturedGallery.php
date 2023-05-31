<?php

namespace XtendLunar\Addons\PageBuilder\Components\Collection;

use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Select;
use XtendLunar\Addons\PageBuilder\Components\Advertisement\AdsWidget;
use XtendLunar\Addons\PageBuilder\Components\CollectionWidget;
use XtendLunar\Addons\PageBuilder\Contracts\Widget;

class FeaturedGallery extends CollectionWidget implements Widget
{
    // @todo Use this property to disable parent grid system for this widget
    public static bool $withoutGridSystem = true;

    public function schema(): array
    {
        return array_merge(parent::schema(), [
            Fieldset::make('Featured Advertisement')
                ->columns(4)
                ->schema([
                    Select::make('params.gallery.layout')->options([
                        'featured_left_4_right' => 'Featured Left (4 Blocks Right)',
                        'featured_right_4_left' => 'Featured Right (4 Blocks Left)',
                        'featured_left_6_right' => 'Featured Left (6 Blocks Right)',
                        'featured_right_6_left' => 'Featured Right (6 Blocks Left)',
                    ])->columnSpanFull(),
                    ...resolve(AdsWidget::class)->schema(),
                ]),
        ]);
    }
}
