<?php

namespace XtendLunar\Addons\PageBuilder\Restify\Presenters;

use Illuminate\Support\Facades\Storage;
use XtendLunar\Addons\RestifyApi\Restify\Contracts\Presentable;
use Binaryk\LaravelRestify\Http\Requests\RestifyRequest;
use XtendLunar\Addons\RestifyApi\Restify\Presenters\PresenterResource;

class CmsPagePresenter extends PresenterResource implements Presentable
{
    public function transform(RestifyRequest $request): array
    {
        return [
            'id' => $this->data['id'],
            'widget_slot_id' => $this->data['widget_slot_id'],
            'image_upload' => $this->data['image_upload'],
            'heading' => $this->repository->model()->translate('heading'),
            'content' => $this->repository->model()->translate('content'),
            'seo' => [
                'title' => $this->repository->model()->translate('seo_title'),
                'description' => $this->repository->model()->translate('seo_description'),
                'image' => $this->data['seo_image'] ? Storage::disk('do')->url($this->data['seo_image']) : null,
            ],
        ];
    }
}


