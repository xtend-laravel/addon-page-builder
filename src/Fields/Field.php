<?php

namespace XtendLunar\Addons\PageBuilder\Fields;

use XtendLunar\Addons\PageBuilder\Fields\Concerns\InteractsWithModel;

abstract class Field
{
    use InteractsWithModel;

    public static function make(): static
    {
        return new static;
    }
}
