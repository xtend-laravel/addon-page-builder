<?php

namespace XtendLunar\Addons\PageBuilder\Components\Collection;

use XtendLunar\Addons\PageBuilder\Components\CollectionWidget;
use XtendLunar\Addons\PageBuilder\Contracts\Widget;

class FeaturedGallery extends CollectionWidget implements Widget
{
    public function schema(): array
    {
        return array_merge(parent::schema(), []);
    }
}
