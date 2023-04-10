<?php

namespace XtendLunar\Addons\PageBuilder\Restify\Presenters;

use Illuminate\Database\Eloquent\Model;
use XtendLunar\Addons\RestifyApi\Restify\Contracts\Presentable;
use Binaryk\LaravelRestify\Http\Requests\RestifyRequest;
use XtendLunar\Addons\RestifyApi\Restify\Presenters\PresenterResource;

class WidgetPresenter extends PresenterResource implements Presentable
{
    protected ?string $modelClassForReplacements;

    public function transform(RestifyRequest $request): array
    {
        if ($this->data['data'] ?? false) {
            $this->modelClassForReplacements = $this->data['data']['model'] ?? null;
            $this->data['data'] = $this->handleDataReplacementsRecursive($this->data['data']);
        }

        if ($this->data['type'] === 'Collection') {
            $this->data['items'] = $this->getter($request, 'items-collection');
        }

        return $this->data;
    }

    protected function handleDataReplacementsRecursive(array $data): array
    {
        return collect($data)->map(
            fn ($item) => is_array($item) ? $this->prepareData(
                item: $this->handleDataReplacementsRecursive($item),
            ) : $item)->toArray();
    }

    protected function prepareData(array $item): array
    {
        return collect($item)->every(
            fn($value) => is_string($value),
        ) ? $this->handleReplacement($item) : $item;
    }

    protected function handleReplacement(array $item): array
    {
        $identifier = $this->queryParams[$this->data['data']['identifier']] ?? null;
        //dd($this->data['data']['lookup_field'], $lookupField, $this->queryParams, $item, $this->modelClassForReplacements);
        //$model = resolve($this->modelClassForReplacements)->find($identifier);
        //dd($model);
        // We need to guess query the model and get the value
        return [];
    }
}


