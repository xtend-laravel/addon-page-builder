<?php

namespace XtendLunar\Addons\PageBuilder;

use Binaryk\LaravelRestify\Traits\InteractsWithRestifyRepositories;
use CodeLabX\XtendLaravel\Base\XtendAddonProvider;
use Illuminate\Support\Facades\Blade;
use Lunar\Hub\Facades\Menu;
use Lunar\Hub\Menu\MenuLink;

class PageBuilderProvider extends XtendAddonProvider
{
    use InteractsWithRestifyRepositories;

    public function register()
    {
        $this->loadRoutesFrom(__DIR__ . '/../route/hub.php');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'xtend-lunar-page-builder');
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'xtend-lunar-page-builder');
        $this->loadRestifyFrom(__DIR__ . '/Restify', __NAMESPACE__ . '\\Restify\\');
    }

    public function boot()
    {
        Blade::componentNamespace('XtendLunar\\Addons\\PageBuilder\\Components', 'xtend-lunar-page-builder');

        Menu::slot('sidebar')
            ->group('hub.configure')
            ->addItem(function (MenuLink $item) {
                return $item->name('Page builder')
                    ->handle('hub.page-builder')
                    ->route('hub.page-builder.index')
                    ->icon('cube');
            });
    }
}
