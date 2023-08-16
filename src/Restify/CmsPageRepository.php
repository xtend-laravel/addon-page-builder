<?php

namespace XtendLunar\Addons\PageBuilder\Restify;

use Binaryk\LaravelRestify\Http\Requests\RestifyRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Str;
use XtendLunar\Addons\PageBuilder\Models\CmsPage;
use XtendLunar\Addons\PageBuilder\Models\WidgetSlot;
use XtendLunar\Addons\PageBuilder\Restify\Presenters\CmsPagePresenter;
use XtendLunar\Addons\RestifyApi\Restify\Repository;

class CmsPageRepository extends Repository
{
    public static string $model = CmsPage::class;

    public static string $presenter = CmsPagePresenter::class;

    public static bool|array $public = true;

    protected static function booting(): void
    {
        $identifier = Str::slug(request()->route()->parameter('repositoryId'), '_');
        $widgetSlot = WidgetSlot::query()->firstWhere('identifier', $identifier);

        if ($widgetSlot) {
            request()->route()->setParameter('repositoryId', $widgetSlot->page->id);
        }
    }
}
