<?php

use Illuminate\Support\Facades\Route;
use Lunar\Hub\Http\Middleware\Authenticate;
use XtendLunar\Addons\PageBuilder\Livewire\Pages\PageBuilderIndex;
use XtendLunar\Addons\PageBuilder\Livewire\WidgetSlots\Create;
use XtendLunar\Addons\PageBuilder\Livewire\WidgetSlots\Edit;

Route::prefix(config('lunar-hub.system.path', 'hub'))
    ->middleware(['web', Authenticate::class, 'can:settings:core'])
    ->group(function () {
        Route::get('/page-builder', PageBuilderIndex::class)->name('hub.page-builder.index');
        Route::get('/page-builder/widget-slots/create', Create::class)->name('hub.page-builder.widget-slots.create');
        Route::get('/page-builder/widget-slots/{widgetSlot}/edit', Edit::class)->name('hub.page-builder.widget-slots.edit');
    });
