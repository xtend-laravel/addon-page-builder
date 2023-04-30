<?php

namespace XtendLunar\Addons\PageBuilder\Restify\Presenters;

use Illuminate\Support\Facades\Storage;
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

        $this->prepareData($this->data);
        return $this->data;
    }

    protected function prepareData(array &$data = [])
    {
        foreach ($data as $key => &$item) {
            is_array($item)
                ? $this->prepareData($item)
                : $item = $this->prefixMediaPath($key, $item);
        }
    }

    protected function prefixMediaPath(string $key, ?string $item): ?string
    {
        if ($item && in_array($key, ['image_upload'])) {
            $item = Storage::url($item);
        }

        return $item;
    }
}


