<?php

namespace XtendLunar\Addons\PageBuilder\Restify\Presenters;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use XtendLunar\Addons\PageBuilder\Models\Widget;
use XtendLunar\Addons\PageBuilder\Models\WidgetSlot;
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

        //$this->overrideDataFromPivot($request);
        $this->prepareData($this->data);
        return $this->data;
    }

    protected function overrideDataFromPivot(RestifyRequest $request)
    {
        // @todo Replace this temp hack to get correct data for language

        $widgetSlotIdentifier = WidgetSlot::find($this->repository->getModel()->pivot->widget_slot_id)->identifier;
        $locale = app()->getLocale();
        $pivotData = $this->data['pivot']->data;
        if (!str_contains($widgetSlotIdentifier, '_'.$locale)) {
            $widgetSlot = WidgetSlot::where('identifier', Str::beforeLast($widgetSlotIdentifier, '_').'_'.$locale)->first();
            if ($widgetSlot) {
                $widget = $widgetSlot->widgets()->where([
                    'widget_id' => $this->data['id'],
                    'widget_slot_id' => $widgetSlot->id,
                ])->first();
                $pivotData = $widget->pivot->data;
            }
        }

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


