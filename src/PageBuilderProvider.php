<?php

namespace XtendLunar\Addons\PageBuilder;

use Binaryk\LaravelRestify\Traits\InteractsWithRestifyRepositories;
use CodeLabX\XtendLaravel\Base\XtendAddonProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Lunar\Hub\Facades\Menu;
use Lunar\Hub\Menu\MenuLink;
use Livewire\Livewire;
use Stephenjude\FilamentBlog\Resources\CategoryResource\Pages\CreateCategory;
use XtendLunar\Addons\PageBuilder\Livewire\Categories\ListCategories;
use XtendLunar\Addons\PageBuilder\Livewire\Posts\CreatePost;
use XtendLunar\Addons\PageBuilder\Livewire\WidgetSlots\Create;
use XtendLunar\Addons\PageBuilder\Livewire\WidgetSlots\Edit;
use XtendLunar\Addons\PageBuilder\Livewire\WidgetSlots\Table;
use XtendLunar\Addons\PageBuilder\Models\CmsPage;
use XtendLunar\Addons\PageBuilder\Models\Form;
use XtendLunar\Addons\PageBuilder\Models\FormSubmission;
use XtendLunar\Addons\PageBuilder\Models\WidgetSlot;
use XtendLunar\Addons\PageBuilder\Policies\CmsPagePolicy;
use XtendLunar\Addons\PageBuilder\Policies\FormPolicy;
use XtendLunar\Addons\PageBuilder\Policies\FormSubmissionPolicy;
use XtendLunar\Addons\PageBuilder\Policies\WidgetSlotPolicy;

class PageBuilderProvider extends XtendAddonProvider
{
    use InteractsWithRestifyRepositories;

    protected $policies = [
        WidgetSlot::class => WidgetSlotPolicy::class,
        CmsPage::class => CmsPagePolicy::class,
        Form::class => FormPolicy::class,
        FormSubmission::class => FormSubmissionPolicy::class,
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
        $this->registerPolicies();
        Blade::componentNamespace('XtendLunar\\Addons\\PageBuilder\\Components', 'xtend-lunar-page-builder');

//        Menu::slot('sidebar')
//            ->group('hub.configure')
//            ->addItem(function (MenuLink $item) {
//                return $item->name('Page builder')
//                    ->handle('hub.page-builder')
//                    ->route('hub.page-builder.index')
//                    ->icon('cube');
//            });

        Menu::slot('sidebar')
            ->group('hub.content')
            ->name('Content')
            ->addItem(function (MenuLink $item) {
                return $item->name(__('Pages'))
                    ->handle('hub.page-builder')
                    ->route('hub.page-builder.index')
                    ->icon('cube');
            })
            ->addItem(function (MenuLink $item) {
                return $item->name(__('Posts'))
                    ->handle('hub.content.posts')
                    ->route('hub.content.posts.index')
                    ->icon('document-text');
            })
            ->addItem(function (MenuLink $item) {
                return $item->name(__('Categories'))
                    ->handle('hub.content.categories')
                    ->route('hub.content.categories.index')
                    ->icon('collection');
            })
        ;
    }

    protected function registerLivewireComponents(): void
    {
        Livewire::component('xtend-lunar-page-builder.widget-slots.table', Table::class);
        Livewire::component('xtend-lunar-page-builder.widget-slots.create', Create::class);
        Livewire::component('xtend-lunar-page-builder.widget-slots.edit', Edit::class);

        Livewire::component('xtend-lunar-page-builder.posts.create-post', CreatePost::class);

        Livewire::component('xtend-lunar-page-builder.categories.create-category', ListCategories::class);
    }

    protected function registerPolicies()
    {
        foreach ($this->policies as $model => $policy) {
            Gate::policy($model, $policy);
        }
    }
}
