<?php

namespace XtendLunar\Addons\PageBuilder;

use Binaryk\LaravelRestify\Traits\InteractsWithRestifyRepositories;
use CodeLabX\XtendLaravel\Base\XtendAddonProvider;
use Illuminate\Support\Facades\Blade;

class PageBuilderProvider extends XtendAddonProvider
{
    use InteractsWithRestifyRepositories;

    public function register()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'xtend-lunar::page-builder');
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'xtend-lunar::page-builder');
        $this->loadRestifyFrom(__DIR__.'/Restify', __NAMESPACE__.'\\Restify\\');
    }

    public function boot()
    {
        Blade::componentNamespace('XtendLunar\\Addons\\PageBuilder\\Components', 'xtend-lunar::page-builder');
    }
}
