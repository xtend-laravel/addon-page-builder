<?php

namespace XtendLunar\Addons\PageBuilder\Restify\Presenters;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use XtendLunar\Addons\RestifyApi\Restify\Contracts\Presentable;
use Binaryk\LaravelRestify\Http\Requests\RestifyRequest;
use XtendLunar\Addons\RestifyApi\Restify\Presenters\PresenterResource;

class BlogPostPresenter extends PresenterResource implements Presentable
{
    public function transform(RestifyRequest $request): array
    {
        $hasBanner = $this->data['banner'] && Storage::disk('do')->exists($this->data['banner']);
        return [
            'id' => $this->data['id'],
            'title' => $this->repository->model()->translate('title'),
            'slug' => $this->data['slug'],
            'excerpt' => $this->repository->model()->translate('excerpt'),
            'content' => $this->repository->model()->translate('content'),
            'banner' => $hasBanner ? Storage::disk('do')->url($this->data['banner']) : null,
            'category' => [
                'id' => $this->repository->model()->category->id,
                'name' => $this->repository->model()->category->translate('name'),
                'slug' => $this->repository->model()->category->slug,
            ],
            'created_at' => Carbon::parse($this->data['created_at'])->format('M d, Y H:i a'),
            'updated_at' => Carbon::parse($this->data['updated_at'])->format('M d, Y H:i a'),
            'seo' => [
                'title' => $this->repository->model()->translate('seo_title'),
                'description' => $this->repository->model()->translate('seo_description'),
                'image' => $this->data['seo_image'] ? Storage::disk('do')->url($this->data['seo_image']) : null,
            ],
        ];
    }
}


