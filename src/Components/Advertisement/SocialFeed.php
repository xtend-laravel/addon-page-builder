<?php

namespace XtendLunar\Addons\PageBuilder\Components\Advertisement;

use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Select;
use XtendLunar\Addons\PageBuilder\Components\AdvertisementWidget;
use XtendLunar\Addons\PageBuilder\Contracts\Widget;

class SocialFeed extends AdvertisementWidget implements Widget
{
    public function schema(): array
    {
        return array_merge(parent::schema(), [
            Fieldset::make('Social Feed')
                ->columns(4)
                ->schema([
                    Select::make('params.feed')->options([
                        'instagram' => 'Instagram',
                        'facebook' => 'Facebook',
                        'twitter' => 'Twitter',
                        'pinterest' => 'Pinterest',
                        'linkedin' => 'Linkedin',
                    ])->columnSpanFull(),
                    ...$this->getSocialFeedSchema(),
                ]),
        ]);
    }

    protected function getSocialFeedSchema(): array
    {
        return [
            Select::make('params.layout')->options([
                'grid' => 'Grid',
                'carousel' => 'Carousel',
            ])->columnSpanFull(),
            Select::make('params.limit')->options([
                '1' => '1',
                '2' => '2',
                '3' => '3',
                '4' => '4',
            ])->columnSpanFull(),
        ];
    }
}
