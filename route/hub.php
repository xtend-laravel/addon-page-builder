<?php

use Illuminate\Support\Facades\Route;
use Lunar\Hub\Http\Middleware\Authenticate;
use XtendLunar\Addons\PageBuilder\Livewire\Pages\PageBuilderIndex;

Route::prefix(config('lunar-hub.system.path', 'hub'))
    ->middleware(['web', Authenticate::class, 'can:settings:core'])
    ->group(function () {
        Route::get('/page-builder', PageBuilderIndex::class)->name('hub.page-builder.index');
        Route::get('/page-builder/widgets/{widget}/edit', \XtendLunar\Addons\PageBuilder\Livewire\Pages\WidgetEdit::class)->name('hub.page-builder.widgets.edit');
    });
