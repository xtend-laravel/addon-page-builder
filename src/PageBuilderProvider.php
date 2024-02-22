<?php

namespace XtendLunar\Addons\PageBuilder;

use Binaryk\LaravelRestify\Traits\InteractsWithRestifyRepositories;
use CodeLabX\XtendLaravel\Base\XtendAddonProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Lunar\Hub\Auth\Manifest;
use Lunar\Hub\Auth\Permission;
use Lunar\Hub\Facades\Menu;
use Lunar\Hub\Menu\MenuLink;
use Livewire\Livewire;
use PageBuilder\Database\Seeders\BlogCategorySeeder;
use XtendLunar\Addons\PageBuilder\Livewire\Categories\CategoryForm;
use XtendLunar\Addons\PageBuilder\Livewire\Categories\ListCategories;
use XtendLunar\Addons\PageBuilder\Livewire\Posts\ListPosts;
use XtendLunar\Addons\PageBuilder\Livewire\Posts\PostForm;
use XtendLunar\Addons\PageBuilder\Livewire\WidgetSlots\Create;
use XtendLunar\Addons\PageBuilder\Livewire\WidgetSlots\Edit;
use XtendLunar\Addons\PageBuilder\Livewire\WidgetSlots\Table;
use XtendLunar\Addons\PageBuilder\Models\CmsPage;
use XtendLunar\Addons\PageBuilder\Models\BlogPost;
use XtendLunar\Addons\PageBuilder\Models\Form;
use XtendLunar\Addons\PageBuilder\Models\FormSubmission;
use XtendLunar\Addons\PageBuilder\Models\WidgetSlot;
use XtendLunar\Addons\PageBuilder\Policies\BlogPostPolicy;
use XtendLunar\Addons\PageBuilder\Policies\CmsPagePolicy;
use XtendLunar\Addons\PageBuilder\Policies\FormPolicy;
use XtendLunar\Addons\PageBuilder\Policies\FormSubmissionPolicy;
use XtendLunar\Addons\PageBuilder\Policies\WidgetSlotPolicy;
use Database\Seeders\DatabaseSeeder;

class PageBuilderProvider extends XtendAddonProvider
{
    use InteractsWithRestifyRepositories;

    protected $policies = [
        WidgetSlot::class => WidgetSlotPolicy::class,
        CmsPage::class => CmsPagePolicy::class,
        BlogPost::class => BlogPostPolicy::class,
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

        $this->registerSeeders([
            BlogCategorySeeder::class,
        ]);
    }

    public function boot()
    {
        $this->registerPolicies();
        Blade::componentNamespace('XtendLunar\\Addons\\PageBuilder\\Components', 'xtend-lunar-page-builder');

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
            });

        $manifest = $this->app->get(Manifest::class);

        $manifest->addPermission(function (Permission $permission) {
            $permission->name = 'Manage Pages';
            $permission->handle = 'hub.page-builder:manage-pages';
            $permission->description = 'Allow the staff member to manage pages';
        });
    }

    protected function registerLivewireComponents(): void
    {
        Livewire::component('xtend-lunar-page-builder.widget-slots.table', Table::class);
        Livewire::component('xtend-lunar-page-builder.widget-slots.create', Create::class);
        Livewire::component('xtend-lunar-page-builder.widget-slots.edit', Edit::class);

        Livewire::component('xtend-lunar.page-builder.posts.list-posts', ListPosts::class);
        Livewire::component('xtend-lunar-page-builder.posts.create-post', PostForm::class);

        Livewire::component('xtend-lunar-page-builder.categories.list-categories', ListCategories::class);
        Livewire::component('xtend-lunar-page-builder.categories.form', CategoryForm::class);
    }

    protected function registerPolicies()
    {
        foreach ($this->policies as $model => $policy) {
            Gate::policy($model, $policy);
        }
    }

    protected function registerSeeders(array $seeders = []): void
    {
        $this->callAfterResolving(DatabaseSeeder::class, function (DatabaseSeeder $seeder) use ($seeders) {
            collect($seeders)->each(
                fn ($seederClass) => $seeder->call($seederClass),
            );
        });
    }
}
