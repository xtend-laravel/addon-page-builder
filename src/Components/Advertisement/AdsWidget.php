<?php

namespace XtendLunar\Addons\PageBuilder\Components\Advertisement;

use XtendLunar\Addons\PageBuilder\Components\AdvertisementWidget;
use XtendLunar\Addons\PageBuilder\Contracts\Widget;

class AdsWidget extends AdvertisementWidget implements Widget
{
    public function schema(): array
    {
        return array_merge(parent::schema(), []);
    }
}
