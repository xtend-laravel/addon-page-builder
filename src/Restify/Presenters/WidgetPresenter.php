<?php

namespace XtendLunar\Addons\PageBuilder\Restify\Presenters;

use XtendLunar\Addons\RestifyApi\Restify\Contracts\Presentable;
use Binaryk\LaravelRestify\Http\Requests\RestifyRequest;
use XtendLunar\Addons\RestifyApi\Restify\Presenters\PresenterResource;

class WidgetPresenter extends PresenterResource implements Presentable
{
    public function transform(RestifyRequest $request): array
    {
        if ($this->data['type'] === 'Collection') {
            $this->data['items'] = $this->getter($request, 'items-collection');
        }

        return $this->data;
    }
}


