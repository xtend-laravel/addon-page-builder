<?php

namespace XtendLunar\Addons\PageBuilder\Restify;

use XtendLunar\Addons\PageBuilder\Models\CmsPage;
use XtendLunar\Addons\PageBuilder\Restify\Presenters\CmsPagePresenter;
use XtendLunar\Addons\RestifyApi\Restify\Repository;

class CmsPageRepository extends Repository
{
    public static string $model = CmsPage::class;

    public static string $presenter = CmsPagePresenter::class;

    public static bool|array $public = true;
}
