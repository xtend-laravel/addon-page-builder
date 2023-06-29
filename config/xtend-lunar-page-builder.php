<?php

use XtendLunar\Addons\PageBuilder\Components\Advertisement;
use XtendLunar\Addons\PageBuilder\Components\Collection;
use XtendLunar\Addons\PageBuilder\Components\Content;

return [
    'components' => [
        'advertisement' => [
            Advertisement\AdsWidget::class,
            Advertisement\FeaturedOverlay::class,
            Advertisement\FeaturedSlider::class,
            Advertisement\Spotlight::class,
            Advertisement\SocialFeed::class,
        ],
        'collection' => [
            Collection\FeaturedCarousel::class,
            Collection\FeaturedGallery::class,
        ],
        'content' => [
            Content\BlockNav::class,
            Content\Heading::class,
        ],
    ]
];
