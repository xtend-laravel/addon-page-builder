<?php

namespace XtendLunar\Addons\PageBuilder\Restify\Presenters;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Lunar\Models\CollectionGroup;
use Xtend\Extensions\Lunar\Core\Models\Collection;
use XtendLunar\Addons\PageBuilder\Models\Widget;
use XtendLunar\Addons\PageBuilder\Models\WidgetSlot;
use XtendLunar\Addons\RestifyApi\Restify\Contracts\Presentable;
use Binaryk\LaravelRestify\Http\Requests\RestifyRequest;
use XtendLunar\Addons\RestifyApi\Restify\Presenters\PresenterResource;

class WidgetPresenter extends PresenterResource implements Presentable
{
    public function transform(RestifyRequest $request): array
    {
        $this->overrideDataFromPivot();

        if ($this->data['type'] === 'Collection') {
            $this->data['items'] = $this->getter($request, 'items-collection');
        }
        $this->prepareData($this->data);

        if ($this->data['component'] === 'ContentBlockNav') {
            $collectionGroup = CollectionGroup::find($this->data['data']['collection_group_id'] ?? 0);
            if ($collectionGroup) {
                $this->data['data']['collections'] = $collectionGroup->collections->map(function (Collection $collection) {
                    return [
                        'id' => $collection->id,
                        'name' => $collection->translateAttribute('name') ?? null,
                        'slug' => $collection->id.'-'.Str::slug($collection->translateAttribute('name')),
                    ];
                });
            }
        }

        return $this->data;
    }

    protected function overrideDataFromPivot()
    {
        $pivotData = $this->data['pivot']->data;
        $pivotData = json_decode($pivotData, true);
        $this->data['data'] = $pivotData ?? $this->data['data'];
    }

    protected function prepareData(array &$data = [])
    {
        foreach ($data as $key => &$item) {
            $item = $this->modifiers($key, $item);
            if (is_array($item)) {
                $this->prepareData($item);
            }
        }
    }

    protected function modifiers(string $key, mixed $item): mixed
    {
        $item = $this->prefixMediaPath($key, $item);
        $item = $this->translate($item);

        return $item;
    }

    protected function prefixMediaPath(string $key, mixed $item): mixed
    {
        if ($item && in_array($key, ['image_upload']) && is_string($item)) {
            $item = Storage::url(urldecode($item));
        }

        return $item;
    }

    protected function translate(mixed $item): mixed
    {
        if (is_array($item)) {
            $locale = app()->getLocale() ?? 'en';
            return $item[$locale] ?? $item;
        }

        return $item;
    }
}


