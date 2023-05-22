<?php

namespace XtendLunar\Addons\PageBuilder\Restify\Presenters;

use XtendLunar\Addons\RestifyApi\Restify\Contracts\Presentable;
use Binaryk\LaravelRestify\Http\Requests\RestifyRequest;
use XtendLunar\Addons\RestifyApi\Restify\Presenters\PresenterResource;

class CmsPagePresenter extends PresenterResource implements Presentable
{
    public function transform(RestifyRequest $request): array
    {
        return $this->data;
    }
}


