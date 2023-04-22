<?php

namespace XtendLunar\Addons\PageBuilder\Components\Content;

use XtendLunar\Addons\PageBuilder\Components\ContentWidget;
use XtendLunar\Addons\PageBuilder\Contracts\Widget;

class Heading extends ContentWidget implements Widget
{
    public function schema(): array
    {
        return array_merge(parent::schema(), []);
    }
}
