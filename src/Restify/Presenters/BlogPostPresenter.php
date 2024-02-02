<?php

namespace XtendLunar\Addons\PageBuilder\Restify\Presenters;

use Illuminate\Support\Facades\Storage;
use XtendLunar\Addons\RestifyApi\Restify\Contracts\Presentable;
use Binaryk\LaravelRestify\Http\Requests\RestifyRequest;
use XtendLunar\Addons\RestifyApi\Restify\Presenters\PresenterResource;

class BlogPostPresenter extends PresenterResource implements Presentable
{
    public function transform(RestifyRequest $request): array
    {
        return [
            'id' => $this->data['id'],
            'title' => $this->repository->model()->translate('title'),
            'slug' => $this->data['slug'],
            'excerpt' => $this->repository->model()->translate('excerpt'),
            'content' => $this->repository->model()->translate('content'),
            'banner' => Storage::disk('do')->url($this->data['banner']),
            'created_at' => $this->data['created_at'],
            'updated_at' => $this->data['updated_at'],
        ];
    }
}


