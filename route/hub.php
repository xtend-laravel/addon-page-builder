<?php

use Illuminate\Support\Facades\Route;
use Lunar\Hub\Http\Middleware\Authenticate;
use XtendLunar\Addons\PageBuilder\Livewire\Pages\PageBuilderIndex;
use XtendLunar\Addons\PageBuilder\Livewire\Posts\CreatePost;
use XtendLunar\Addons\PageBuilder\Livewire\Posts\ListPosts;
use XtendLunar\Addons\PageBuilder\Livewire\WidgetSlots\CloneSlot;
use XtendLunar\Addons\PageBuilder\Livewire\WidgetSlots\Create;
use XtendLunar\Addons\PageBuilder\Livewire\WidgetSlots\Edit;

Route::prefix(config('lunar-hub.system.path'))
    ->middleware(['web', Authenticate::class, 'can:settings:core'])
    ->group(function () {
        /**
         * Page routes
         */
        Route::get('/page-builder', PageBuilderIndex::class)->name('hub.page-builder.index');
        Route::get('/page-builder/widget-slots/create', Create::class)->name('hub.page-builder.widget-slots.create');
        Route::get('/page-builder/widget-slots/{widgetSlot}/edit', Edit::class)->name('hub.page-builder.widget-slots.edit');
        Route::get('/page-builder/widget-slots/{widgetSlot}/clone', CloneSlot::class)->name('hub.page-builder.widget-slots.clone');

        /**
         * Post routes
         */
        Route::get('/posts', ListPosts::class)->name('hub.content.posts.index');
        Route::get('/posts/create', CreatePost::class)->name('hub.content.posts.create');

        /**
         * Category routes
         */
        Route::get('/categories', fn() => 'category')->name('hub.content.categories.index');
    });
