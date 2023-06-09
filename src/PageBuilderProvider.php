<?php

namespace XtendLunar\Addons\PageBuilder;

use Binaryk\LaravelRestify\Traits\InteractsWithRestifyRepositories;
use CodeLabX\XtendLaravel\Base\XtendAddonProvider;
use Illuminate\Support\Facades\Blade;
use Lunar\Hub\Facades\Menu;
use Lunar\Hub\Menu\MenuLink;
use Livewire\Livewire;
use XtendLunar\Addons\PageBuilder\Livewire\WidgetSlots\Create;
use XtendLunar\Addons\PageBuilder\Livewire\WidgetSlots\Edit;
use XtendLunar\Addons\PageBuilder\Livewire\WidgetSlots\Table;
use XtendLunar\Addons\PageBuilder\Models\CmsPage;
use XtendLunar\Addons\PageBuilder\Models\WidgetSlot;
use XtendLunar\Addons\PageBuilder\Policies\CmsPagePolicy;
use XtendLunar\Addons\PageBuilder\Policies\WidgetSlotPolicy;

class PageBuilderProvider extends XtendAddonProvider
{
    use InteractsWithRestifyRepositories;

    protected $policies = [
        WidgetSlot::class => WidgetSlotPolicy::class,
        CmsPage::class => CmsPagePolicy::class,
    ];

    public function register()
    {
        $this->loadRoutesFrom(__DIR__ . '/../route/hub.php');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'xtend-lunar-page-builder');
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'xtend-lunar-page-builder');
        $this->loadRestifyFrom(__DIR__ . '/Restify', __NAMESPACE__ . '\\Restify\\');
        $this->mergeConfigFrom(__DIR__ . '/../config/xtend-lunar-page-builder.php', 'xtend-lunar-page-builder');

        $this->registerLivewireComponents();
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

    protected function registerLivewireComponents(): void
    {
        Livewire::component('xtend-lunar-page-builder.widget-slots.table', Table::class);
        Livewire::component('xtend-lunar-page-builder.widget-slots.create', Create::class);
        Livewire::component('xtend-lunar-page-builder.widget-slots.edit', Edit::class);
    }
}
