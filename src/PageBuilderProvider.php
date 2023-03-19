<?php

namespace XtendLunar\Addons\PageBuilder;

use CodeLabX\XtendLaravel\Base\XtendAddonProvider;
use Illuminate\Support\Facades\Blade;

class PageBuilderProvider extends XtendAddonProvider
{
    public function register()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'xtend-lunar::page-builder');
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'xtend-lunar::page-builder');
    }

    public function boot()
    {
        Blade::componentNamespace('XtendLunar\\Addons\\PageBuilder\\Components', 'xtend-lunar::page-builder');
    }
}
