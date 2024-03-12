<?php

namespace XtendLunar\Addons\PageBuilder\Restify\Presenters;

use Illuminate\Support\Facades\Storage;
use XtendLunar\Addons\RestifyApi\Restify\Contracts\Presentable;
use Binaryk\LaravelRestify\Http\Requests\RestifyRequest;
use XtendLunar\Addons\RestifyApi\Restify\Presenters\PresenterResource;

class WidgetSlotPresenter extends PresenterResource implements Presentable
{
    public function transform(RestifyRequest $request): array
    {
        $this->data['seo'] = [
            'title' => $this->repository->model()->translate('seo_title'),
            'description' => $this->repository->model()->translate('seo_description'),
            'image' => $this->data['seo_image'] ? Storage::disk('do')->url($this->data['seo_image']) : null,
        ];

        unset($this->data['seo_title']);
        unset($this->data['seo_description']);
        unset($this->data['seo_keywords']);
        unset($this->data['seo_image']);

        return $this->data;
    }
}


