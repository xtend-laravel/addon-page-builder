<?php

namespace XtendLunar\Addons\PageBuilder\Restify;

use XtendLunar\Addons\PageBuilder\Restify\Presenters\WidgetPresenter;
use XtendLunar\Addons\RestifyApi\Restify\Getters\Lunar;
use Binaryk\LaravelRestify\Http\Requests\RestifyRequest;
use Xtend\Extensions\Lunar\Core\Models\Widget;
use XtendLunar\Addons\RestifyApi\Restify\Repository;

class WidgetRepository extends Repository
{
    public static string $model = Widget::class;

    public static string $presenter = WidgetPresenter::class;

    public static bool|array $public = true;

    public function getters(RestifyRequest $request): array
    {
        return [
            Lunar\ItemsCollectionGetter::new(),
        ];
    }
}
